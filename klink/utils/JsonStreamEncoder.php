<?php

/**
 * Json Encoder that writes the json to a stream.
 */
final class JsonStreamEncoder
{
	private $_stream;

	/**
	 * @param resource $stream A stream resource. If null a new temporary stream will be created. Default null.
	 * @throws \InvalidArgumentException If $stream is not a stream resource.
	 */
	public function __construct( $stream = null )
	{
        if(is_null($stream)){
            $stream = tmpfile();
        }
        
		if (!is_resource($stream) || get_resource_type($stream) != 'stream') {
			throw new \InvalidArgumentException("Resource is not a stream");
		}

		$this->_stream = $stream;
	}

	/**
	 * Encodes a value and writes it to the stream.
	 *
	 * @param mixed $value
	 */
	public function encode($value)
	{
		// null, bool and scalar values
		if(is_null($value)) {
			$this->_writeValue('null');
			return;
		}
		elseif ($value === false) {
			$this->_writeValue('false');
			return;
		}
		elseif ($value === true) {
			$this->_writeValue('true');
			return;
		}
		elseif (is_scalar($value)) {
			$this->_encodeScalar($value);
			return;
		}

        
        if( is_resource($value) && @get_resource_type($value) === 'stream' ){
            // if it is a PHP stream
            
            $fstat = fstat($value);
            $size = $fstat['size'];
            $chunk_size = 128*1024;
            $remaining = $size;
            
            // I have a stream
            $this->_writeValue('"');
            
            while($remaining > 0){
               $this->_writeValue(fread($value, $chunk_size));
               $remaining = $remaining - $chunk_size;  
            }
            $this->_writeValue('"');
            
            fclose($value); // Let's close the original stream when we have done so PHP can garbage collect some garbage
            
            return;
        }
        else if( @get_resource_type($value) === 'Unknown' ){
            
            // I had a stream
            throw new UnexpectedValueException('Input Stream was closed'); 
            return;
        }
		// array of values
		else if ($this->_isList($value)) {
			$this->_encodeList($value);
			return;
		}
		// objects and associative arrays
		else {
			$this->_encodeObject($value);
			return;
		}
	}
    
    
    /**
     * Return the JSON stream.
	 *
	 * Rewinds the stream that contains the json and return it.
	 *
	 * @return resource The JSON stream
     */
    public function getJsonStream(){
        fseek($this->_stream, 0);
        return $this->_stream;
    }
    
	/**
     * Close the JSON stream and free the internal resource used
	 *
	 * @return void
     */
    public function closeJsonStream(){
		
		if(@get_resource_type($this->_stream) !== 'Unknown'){
			fclose($this->_stream);
        	unset($this->_stream);
		}

    }
    

	/**
	 * Writes a value to the stream.
	 *
	 * @param string $value
	 */
	private function _writeValue($value)
	{
		fwrite($this->_stream, $value);
	}

	/**
	 * Encodes a scalar value.
	 *
	 * @param mixed $value
	 */
	private function _encodeScalar($value)
	{
		if (is_float($value)) {
			// Always use "." for floats.
			$encodedValue = floatval(str_replace(",", ".", strval($value)));
		}
		elseif (is_string($value)) {
			$encodedValue = $this->_encodeString($value);
		}
		else {
			// otherwise this must be an int
			$encodedValue = $value;
		}

		$this->_writeValue($encodedValue);
	}

	/**
	 * Encodes a string.
	 *
	 * @param $string
	 * @return string
	 */
	private function _encodeString($string)
	{
		static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"', "\0"), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"', '\u0000'));
		return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $string) . '"';
	}

	/**
	 * Checks if a value is a flat list of values (simple array) or a map (assoc. array or object).
	 *
	 * @param mixed $value
	 * @return bool
	 */
	private function _isList($value)
	{
		// objects that are not explicitly traversable could never have integer keys, therefore they are not a list
		if (is_object($value) && !($value instanceof \Traversable)) {
			return false;
		}

		// check if the array/object has only integer keys.
		$i = 0;
		foreach ($value as $key => $element) {
			if ($key !== $i) {
				return false;
			}
			$i++;
		}

		return true;
	}

	/**
	 * Encodes a list of values.
	 *
	 * @param array $list
	 */
	private function _encodeList($list)
	{
		$this->_writeValue('[');

		foreach ($list as $x => $value) {
			$this->encode($value);

			if ($x < count($list) - 1) {
				$this->_writeValue(',');
			}
		}

		$this->_writeValue(']');
	}

	/**
	 * Encodes an object or associative array.
	 *
	 * @param array|object $object
	 */
	private function _encodeObject($object)
	{
		$this->_writeValue('{');

		$firstIteration = true;

		foreach ($object as $key => $value) {
            
			if (!$firstIteration) {
				$this->_writeValue(',');
			}
			$firstIteration = false;

			$this->_encodeScalar((string)$key);
			$this->_writeValue(':');
			$this->encode($value);
		}

		$this->_writeValue('}');
	}
}
