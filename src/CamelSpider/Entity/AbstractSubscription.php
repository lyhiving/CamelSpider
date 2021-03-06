<?php 

namespace CamelSpider\Entity;

use Doctrine\Common\Collections\ArrayCollection,
    CamelSpider\Entity\InterfaceLink,
    CamelSpider\Entity\Link;

abstract class AbstractSubscription extends ArrayCollection implements InterfaceSubscription
{

    public function setStatus($x)
    {
        return $this->set('status', $x);
    }

    public function getId($mode = null)
    {
        return $this->get('id');
    }



    /**
     * normalize espaces after commas
     *
     * @param string $x   String to explode
     *
     * @return string
     */
    public function normalize($x)
    {
        return str_replace(array(' ,', ', '), array(',',','), $x);
    }

    /**
     * Returns an array from a value by exploding
     *
     * @param string $x   String to explode
     * @param string $sep The separator (default to comma)
     *
     * @return array
     */
    public function _explode($x, $sep=',')
    {
        if (strpos($x, $sep) !== false) {
            return explode($sep, $this->normalize($x));
        } else {
            return array($x);
        }
    }

    public function getDomain()
    {
        return $this->_explode($this->get('domain'));
    }

    public function getHref()
    {
        return $this->get('href');
    }

    public function getFilters()
    {
        return $this->get('filters');
    }

    /**
     * @param string $type contain|notContain
     */
    public function getFilter($type)
    {
        $filters = $this->getFilters();
        return $this->_explode($filters[$type]);
    }

    public function getDomainString()
    {
        return implode(',', $this->getDomain());
    }
    public function __toString()
    {
        return $this->getDomainString();
    }

    public function getMaxDepth()
    {
        return $this->get('max_depth');
    }

    public function getLink($sha1)
    {
        //make somethin cool with your DB!
        return false;
    }
    public function isDone()
    {
        return true;
    }
    public function isWaiting()
    {
        return false;
    }

    public function toMinimal()
    {
        return $this;
    }

    protected function inDomain($str)
    {
        foreach($this->getDomain() as $domain)
        {
            if(stripos($str, $domain))
            {
                    return true;
            }
        }
    }

    public function insideScope(Link $link)
    {
        if (
            substr($link->get('href'), 0, 4) == 'http' && 
            !$this->inDomain($link->get('href'))
        ) {
            return false;
        }

        return true;
    }

}



