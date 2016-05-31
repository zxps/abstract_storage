<?php

namespace Storage\Transmitter;

use Storage\ACID\AtomicConsistencyException;
use Storage\ACID\AtomicOperation;
use Storage\Filter\Filter;
use Storage\IO\OutputStream;
use Storage\Resource\Resource;
use Storage\StorageException;
use Storage\Translator\DefaultTranslator;
use Storage\Translator\Translator;

class Transmitter
{

    /**
     * @var Resource
     */
    protected $source;

    /**
     * @var Resource
     */
    private $destination;

    /**
     * @var \ArrayIterator
     */
    protected $filters;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param Resource $source
     * @param Resource $destination
     * @param Translator $translator ( if null - will use DefaultDataTranslator )
     */
    public function __construct(Resource $source, Resource $destination, Translator $translator = null)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->filters = new \ArrayIterator([]);
        $this->translator = $translator;
        if (null === $this->translator) {
            $this->translator = new DefaultTranslator();
        }
    }

    /**
     * @param Filter $filter
     * @return Transmitter
     */
    public function addFilter(Filter $filter)
    {
        $this->filters->append($filter);
        return $this;
    }

    /**
     * @param Filter[] $filters
     * @return Transmitter
     */
    public function addFilters(array $filters)
    {
        foreach($filters as $filter) {
            if (!($filter instanceof Filter)) {
                throw new \InvalidArgumentException('Must be an instance of Filter interface');
            }
            $this->filters->append($filter);
        }
        return $this;
    }

    /**
     * Starts transmit
     */
    final public function transmit()
    {
        $this->initialize();
        $this->start();
        $this->finish();
    }

    /**
     * Initialize transmitting process
     */
    protected function initialize()
    {
        if ($this->filters->count() > 0) {
            $this->filters->uasort(function (Filter $filter1, Filter $filter2) {
                return ($filter1->getPriority() - $filter2->getPriority());
            });
        }
        $this->atomic([$this->destination]);
    }

    protected function start()
    {
        $this->execute(
            $this->source,
            $this->destination,
            $this->source->getInputStream(),
            $this->destination->getOutputStream()
        );
    }

    protected function finish()
    {
        $this->atomicApply([$this->destination]);
    }

    protected function checkAtomic(array $destinations)
    {
        $count = 0;
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $count++;
            }
        }
        if ($count > 0 && count($destinations) != $count) {
            throw new AtomicConsistencyException();
        }
    }

    /**
     * Starts transaction
     *
     * @param array $destinations
     * @throws AtomicConsistencyException
     */
    protected function atomic(array $destinations)
    {
        $this->checkAtomic($destinations);
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->begin();
            }
        }
    }

    /**
     * Rollback transmission process
     *
     * @param array $destinations
     */
    protected function atomicRollback(array $destinations)
    {
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->rollback();
            }
        }
    }

    /**
     * Commits all
     *
     * @param array $destinations
     */
    protected function atomicApply(array $destinations)
    {
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->commit();
            }
        }
    }

    /**
     * @param Resource $source
     * @param Resource $destination
     * @param InputStream $input
     * @param OutputStream $out
     */
    final protected function execute(Resource $source, Resource $destination, InputStream $input, OutputStream $out)
    {
        try{
            foreach($input as $data) {
                $this->write($source, $destination, $out, $data);
            }
        } catch (StorageException $e) {
            $this->atomicRollback([$this->destination]);
            throw new \RuntimeException('Unable to transmit. Reason: ' . $e->getMessage());
        }
    }


    /**
     * @param Resource $source
     * @param Resource $dest
     * @param OutputStream $stream
     * @param $data
     */
    protected function write(Resource $source, Resource $dest, OutputStream $stream, $data)
    {
        if ($this->filters->count() > 0) {
            foreach($this->filters as $filter) {
                if ($filter->skip($source, $dest, $data)) {
                    return;
                }
                $data = $filter->handle($source, $dest, $data);
            }
        }
        $stream->write($this->translator->translate($data));
    }
}