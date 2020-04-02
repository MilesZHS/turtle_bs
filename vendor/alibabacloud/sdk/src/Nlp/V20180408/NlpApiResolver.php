<?php

namespace AlibabaCloud\Nlp\V20180408;

use AlibabaCloud\Client\Resolver\ApiResolver;

/**
 * @method IE iE(array $options = [])
 * @method KWE kWE(array $options = [])
 * @method TextStructure textStructure(array $options = [])
 * @method ReviewAnalysis reviewAnalysis(array $options = [])
 * @method WordSegment wordSegment(array $options = [])
 * @method Entity entity(array $options = [])
 * @method WordPos wordPos(array $options = [])
 * @method Sentiment sentiment(array $options = [])
 * @method Translate translate(array $options = [])
 */
class NlpApiResolver extends ApiResolver
{
}

class Roa extends \AlibabaCloud\Client\Resolver\Roa
{
    /** @var string */
    public $product = 'Nlp';

    /** @var string */
    public $version = '2018-04-08';

    /** @var string */
    public $method = 'POST';

    /** @var string */
    public $serviceCode = 'nlp';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class IE extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/ie/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class KWE extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/kwe/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class TextStructure extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/textstructure/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class ReviewAnalysis extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/reviewanalysis/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class WordSegment extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/wordsegment/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class Entity extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/entity/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class WordPos extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/wordpos/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class Sentiment extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/sentiment/[Domain]';
}

/**
 * @method string getDomain()
 * @method $this withDomain($value)
 */
class Translate extends Roa
{
    /** @var string */
    public $pathPattern = '/nlp/person/translate/[Domain]';
}
