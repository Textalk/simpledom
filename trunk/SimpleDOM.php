<?php
/*

Copyright 2007 The SimpleDOM Working Group Initiative

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/
class SimpleDOM extends SimpleXMLElement
{
	//=================================
	// DOM stuff
	//=================================

	public function __call($name, $args)
	{
		$passthrough = array(
			'appendChild'	=> 'insert',
			'insertBefore'	=> 'insert',
			'replaceChild'	=> 'insert',

			'getAttribute'				=> 'method',
			'getAttributeNS'			=> 'method',
			'getElementsByTagName'		=> 'method',
			'getElementsByTagNameNS'	=> 'method',
			'hasAttribute'				=> 'method',
			'hasAttributeNS'			=> 'method',
			'removeAttribute'			=> 'method',
			'removeAttributeNS'			=> 'method',
			'removeChild'				=> 'method',
			'setAttribute'				=> 'method',
			'setAttributeNS'			=> 'method',

			'nodeName'			=> 'property',
			'nodeValue'			=> 'property',
			'nodeType'			=> 'property',
			'parentNode'		=> 'property',
			'childNodes'		=> 'property',
			'firstChild'		=> 'property',
			'lastChild'			=> 'property',
			'previousSibling'	=> 'property',
			'nextSibling'		=> 'property',
			'namespaceURI'		=> 'property',
			'prefix'			=> 'property',
			'localName'			=> 'property',
			'textContent'		=> 'property'
		);

		if (!isset($passthrough[$name]))
		{
			throw new BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $name . '()');
		}

		$tmp = dom_import_simplexml($this);

		switch ($passthrough[$name])
		{
			case 'insert':
				if (isset($args[0])
				 && $args[0] instanceof SimpleXMLElement)
				{
					$args[0] = $tmp->ownerDocument->importNode(dom_import_simplexml($args[0]), true);
				}
				// no break; here

			case 'method':
				foreach ($args as &$arg)
				{
					if ($arg instanceof SimpleXMLElement)
					{
						$arg = dom_import_simplexml($arg);
					}
				}
				unset($arg);

				$ret = call_user_func_array(array($tmp, $name), $args);
				break;

			case 'property':
				$ret = $tmp->$name;
				break;
		}

		if ($ret instanceof DOMText)
		{
			return $ret->textContent;
		}

		if ($ret instanceof DOMNode)
		{
			return simplexml_import_dom($ret, get_class($this));
		}

		if ($ret instanceof DOMNodeList)
		{
			$class	= get_class($this);
			$list	= array();
			$i		= -1;

			while (++$i < $ret->length)
			{
				$node = $ret->item($i);
				$list[$i] = ($node instanceof DOMText) ? $node->textContent : simplexml_import_dom($node, $class);
			}

			return $list;
		}

		return $ret;
	}


	//=================================
	// DOM convenience methods
	//=================================

	/**
	* Add a new child after a reference node
	*
	* @param	SimpleXMLElement	$new	New node
	* @param	SimpleXMLElement	$ref	Reference node
	* @return	SimpleDOM					The inserted node
	*/
	public function insertAfter(SimpleXMLElement $new, SimpleXMLElement $ref = null)
	{
		if (!isset($ref)
		 || !($next = dom_import_simplexml($ref)->nextSibling))
		{
			return $this->appendChild($new);
		}

		return $this->insertBefore($new, $next);
	}

	/**
	* Add a new sibling before this node
	*
	* This is a convenience method. The same result can be achieved with
	* {{{
	* $node->parentNode()->insertBefore($new, $node);
	* }}}
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SimpleDOM					The inserted node
	*/
	public function insertBeforeSelf(SimpleXMLElement $new)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		/**
		* We don't want to insert anything before the root node
		*/
		if ($tmp === $tmp->ownerDocument->documentElement)
		{
			throw new BadMethodCallException('insertBeforeSelf() cannot be used to insert nodes outside of the root node');
		}

		$node = $tmp->parentNode->insertBefore($new, $tmp);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Add a new sibling after this node
	*
	* This is a convenience method. The same result can be achieved with
	* {{{
	* $node->parentNode()->insertBefore($new, $node->nextSibling());
	* }}}
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SimpleDOM					The inserted node
	*/
	public function insertAfterSelf(SimpleXMLElement $new)
	{
		$tmp = dom_import_simplexml($this);
		$new = $tmp->ownerDocument->importNode(dom_import_simplexml($new), true);

		/**
		* We don't want to insert anything after the root node
		*/
		if ($tmp === $tmp->ownerDocument->documentElement)
		{
			throw new BadMethodCallException('insertAfterSelf() cannot be used to insert nodes outside of the root node');
		}

		if (isset($tmp->nextSibling))
		{
			$node = $tmp->parentNode->insertBefore($new, $tmp->nextSibling);
		}
		else
		{
			$node = $tmp->parentNode->appendChild($new);
		}

		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Delete this node from document
	*
	* This is a convenience method. The same result can be achieved with
	* {{{
	* $node->parentNode()->removeChild($node);
	* }}}
	*
	* @return	void
	*/
	public function deleteSelf()
	{
		$tmp = dom_import_simplexml($this);

		if ($tmp === $tmp->ownerDocument->documentElement)
		{
			throw new BadMethodCallException('deleteSelf() cannot be used to delete the root node');
		}

		$tmp->parentNode->removeChild($tmp);
	}

	/**
	* Remove this node from document
	*
	* This is a convenience method. The same result can be achieved with
	* {{{
	* $node->parentNode()->removeChild($node);
	* }}}
	*
	* @return	SimpleDOM		The removed node
	*/
	public function removeSelf()
	{
		$tmp = dom_import_simplexml($this);

		if ($tmp === $tmp->ownerDocument->documentElement)
		{
			throw new BadMethodCallException('removeSelf() cannot be used to remove the root node');
		}

		$node = $tmp->parentNode->removeChild($tmp);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Replace this node
	*
	* This is a convenience method. The same result can be achieved with
	* {{{
	* $node->parentNode()->replaceChild($new, $node);
	* }}}
	*
	* @param	SimpleXMLElement	$new	New node
	* @return	SimpleDOM					Replaced node on success
	*/
	public function replaceSelf(SimpleXMLElement $new)
	{
		$old = dom_import_simplexml($this);
		$new = $old->ownerDocument->importNode(dom_import_simplexml($new), true);

		$node = $old->parentNode->replaceChild($new, $old);
		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Delete all elements matching a XPath expression
	*
	* @param	string	$xpath	XPath expression
	* @return	integer			Number of nodes removed
	*/
	public function deleteNodes($xpath)
	{
		if (!is_string($xpath))
		{
			throw new InvalidArgumentException('Argument 1 passed to deleteNodes() must be a string, ' . gettype($xpath) . ' given');
		}

		$nodes = $this->_xpath($xpath);

		if (isset($nodes[0]))
		{
			$tmp = dom_import_simplexml($nodes[0]);

			if ($tmp === $tmp->ownerDocument->documentElement)
			{
				unset($nodes[0]);
			}
		}

		foreach ($nodes as $node)
		{
			$node->deleteSelf();
		}

		return count($nodes);
	}

	/**
	* Remove all elements matching a XPath expression
	*
	* @param	string	$xpath	XPath expression
	* @return	array			Array of removed nodes on success or FALSE on failure
	*/
	public function removeNodes($xpath)
	{
		if (!is_string($xpath))
		{
			throw new InvalidArgumentException('Argument 1 passed to removeNodes() must be a string, ' . gettype($xpath) . ' given');
		}

		$nodes = $this->_xpath($xpath);

		if (isset($nodes[0]))
		{
			$tmp = dom_import_simplexml($nodes[0]);

			if ($tmp === $tmp->ownerDocument->documentElement)
			{
				unset($nodes[0]);
			}
		}

		$return = array();
		foreach ($nodes as $node)
		{
			$return[] = $node->removeSelf();
		}

		return $return;
	}

	/**
	* Remove all elements matching a XPath expression
	*
	* @param	string				$xpath	XPath expression
	* @param	SimpleXMLElement	$new	Replacement node
	* @return	array						Array of replaced nodes on success or FALSE on failure
	*/
	public function replaceNodes($xpath, SimpleXMLElement $new)
	{
		if (!is_string($xpath))
		{
			throw new InvalidArgumentException('Argument 1 passed to replaceNodes() must be a string, ' . gettype($xpath) . ' given');
		}

		$nodes = array();
		foreach ($this->_xpath($xpath) as $node)
		{
			$nodes[] = $node->replaceSelf($new);
		}

		return $nodes;
	}

	/**
	* Copy all attributes from a node to current node
	*
	* @param	SimpleXMLElement	$src	Source node
	* @return	SimpleDOM					Current node
	*/
	public function copyAttributesFrom(SimpleXMLElement $src)
	{
		foreach (dom_import_simplexml($src)->attributes as $attr)
		{
			$this->addAttribute($attr->nodeName, $attr->nodeValue, $attr->namespaceURI);
		}
		return $this;
	}

	/**
	* Clone all children from a node and add them to current node
	*
	* @param	SimpleXMLElement	$src	Source node
	* @return	SimpleDOM					Current node
	*/
	public function copyChildrenFrom(SimpleXMLElement $src)
	{
		$src = dom_import_simplexml($src);
		$dst = dom_import_simplexml($this);

		foreach ($src->childNodes as $child)
		{
			$dst->appendChild($child->cloneNode(true));
		}

		return $this;
	}

	/**
	* Remove all children from a node and add them to current node
	*
	* @param	SimpleXMLElement	$src	Source node
	* @return	SimpleDOM					Current node
	*/
	public function stealChildrenFrom(SimpleXMLElement $src)
	{
		$src = dom_import_simplexml($src);
		$dst = dom_import_simplexml($this);

		while ($src->childNodes->length)
		{
			$child = $src->childNodes->item(0);
			$dst->appendChild($child->parentNode->removeChild($child));
		}

		return $this;
	}

	/**
	* Remove a node from the tree and append it to current node
	*
	* @param	SimpleXMLElement	$node	Target node
	* @return	SimpleDOM					Target node
	*/
	public function stealNode(SimpleXMLElement $node)
	{
		$dst  = dom_import_simplexml($this);
		$node = dom_import_simplexml($node);

		return simplexml_import_dom($dst->appendChild($node->parentNode->removeChild($node)), get_class($this));
	}

	/**
	* Move current node to a new parent
	*
	* @param	SimpleXMLElement	$dst	Target parent
	* @return	SimpleDOM					Current node
	*/
	public function moveTo(SimpleXMLElement $dst)
	{
		$dst  = dom_import_simplexml($dst);
		$node = dom_import_simplexml($this);

		return simplexml_import_dom($dst->appendChild($node->parentNode->removeChild($node)), get_class($this));
	}


	//=================================
	// DOM extra
	//=================================

	/**
	* 
	*
	* @return	void
	*/
	public function insertComment($text, $mode = 'append')
	{
		$this->insert('Comment', $text, $mode);
		return $this;
	}

	/**
	* 
	*
	* @param	string		$text	Text to append
	* @return	SimpleDOM			Current node
	*/
	public function insertText($text, $mode = 'append')
	{
		$this->insert('TextNode', $text, $mode);
		return $this;
	}


	/**
	* Append raw XML data
	*
	* NOTE: if you append a text node, then its closest ancestor that isn't a text node
	*       will be returned instead
	*
	* @see http://php.net/manual/function.dom-domdocumentfragment-appendxml.php
	*
	* @param	string		$xml	XML to append
	* @return	SimpleDOM			The appended node
	*/
	public function insertXML($xml, $mode = 'append')
	{
		if (!is_string($xml))
		{
			throw new InvalidArgumentException('Argument 1 passed to appendXML() must be a string, ' . gettype($xml) . ' given');
		}

		$tmp = dom_import_simplexml($this);
		$fragment = $tmp->ownerDocument->createDocumentFragment();

		/**
		* Disable error reporting
		*/
		$error_reporting = error_reporting();
		error_reporting(0);

		if (!$fragment->appendXML($xml))
		{
			/**
			* Could not append that XML... but why? We are going to check whether
			* the XML is valid.
			*/
			try
			{
				new SimpleXMLElement($xml);
				$exception = new UnexpectedValueException('DOM could not append XML (reason unknown)');
			}
			catch (Exception $e)
			{
				$exception = new InvalidArgumentException($e->getMessage());
			}

			error_reporting($error_reporting);
			throw $exception;
		}

		$node = $this->insertNode($tmp, $fragment, $mode);

		/**
		* Restore error reporting
		*/
		error_reporting($error_reporting);

		/**
		* SimpleXML can't handle text nodes, therefore we return the closest parent
		* that isn't a text node
		*/
		while ($node instanceof DOMText)
		{
			$node = $node->parentNode;
		}

		return simplexml_import_dom($node, get_class($this));
	}

	/**
	* Add a Processing Instruction at the top of the document
	*
	* Processing Instructions are inserted in order, right before the root node.
	* The content of the PI can be passed either as string or as an associative array.
	*
	* @param	string			$target		Target of the processing instruction
	* @param	string|array	$data		Content of the processing instruction
	* @return	bool						TRUE on success, FALSE on failure
	*/
	public function insertPI($target, $data = null, $mode = 'before')
	{
		$tmp = dom_import_simplexml($this);
		$doc = $tmp->ownerDocument;

		if (isset($data))
		{
			if (is_array($data))
			{
				$str = '';
				foreach ($data as $k => $v)
				{
					$str .= $k . '="' . htmlspecialchars($v) . '" ';
				}

				$data = substr($str, 0, -1);
			}
			elseif (!is_string($data))
			{
				throw new InvalidArgumentException('Argument 2 passed to addProcessingInstruction() must be an array or a string, ' . gettype($xml) . ' given');
			}

			$pi = $doc->createProcessingInstruction($target, $data);
		}
		else
		{
			$pi = $doc->createProcessingInstruction($target);
		}

		if ($pi !== false)
		{
			$this->insertNode($tmp, $pi, $mode);
		}

		return $this;
	}

	/**
	* Set several attributes at once
	*
	* @param	array		$attr
	* @return	SimpleDOM			Current node
	*/
	public function setAttributes(array $attr)
	{
		foreach ($attr as $k => $v)
		{
			$this->setAttribute($k, $v);
		}
		return $this;
	}

	/**
	* Return the current element as a DOMElement
	*
	* @return	DOMElement
	*/
	public function asDOM()
	{
		return dom_import_simplexml($this);
	}


	//=================================
	// Internal stuff
	//=================================

	protected function _xpath($xpath)
	{
		if (!libxml_use_internal_errors())
		{
			$restore = true;
			libxml_use_internal_errors(true);
		}

		$nodes = $this->xpath($xpath);

		if (isset($restore))
		{
			libxml_use_internal_errors(false);
		}

		if ($nodes === false)
		{
			throw new InvalidArgumentException('Invalid XPath expression ' . $xpath);
		}

		return $nodes;
	}

	protected function insert($type, $content, $mode)
	{
		$tmp	= dom_import_simplexml($this);
		$method = 'create' . $type;

		$node = $tmp->ownerDocument->$method($content);
		return $this->insertNode($tmp, $node, $mode);
	}

	protected function insertNode(DOMNode $tmp, DOMNode $node, $mode)
	{
		switch ($mode)
		{
			case 'before':
				return $tmp->parentNode->insertBefore($node, $tmp);

			case 'after':
				if ($tmp->nextSibling)
				{
					return $tmp->parentNode->insertBefore($node, $tmp->nextSibling);
				}

				return $tmp->parentNode->appendChild($node);

			case 'append':
			default:
				return $tmp->appendChild($node);
		}
	}
}