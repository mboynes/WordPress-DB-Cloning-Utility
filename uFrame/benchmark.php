<?php

class Benchmark
{
	/*!
	Creates a new Benchmark object.
	*/
	function Benchmark( $start = true )
	{
		if ($start) $this->start();
	}

	/*!
	Starts a new benchmark.
	*/
	function start()
	{
		$this->StartTime = microtime();
	}

	/*!
	Stops the benchmark interval.
	*/
	function stop()
	{
		$this->StopTime = microtime();
	}

	/*!
	Prints the benchmark results.
	*/
	function elapsed()
	{

		$time_1 = explode( " ", $this->StartTime );
		if ($this->StopTime)
			$time_2 = explode( " ", $this->StopTime );
		else
			$time_2 = explode( " ", microtime() );

		preg_match( "/0\.([0-9]+)/", "" . $time_1[0], $t1 );
		preg_match( "/0\.([0-9]+)/", "" . $time_2[0], $t2 );

		$dsec = intval($time_2[1]) - intval($time_1[1]);
		$t2 = doubleval("$dsec." . $t2[1]);
		$t1 = doubleval("0." . $t1[1]);

		$result = floatval($t2 - $t1);
		if ($result < 0.0) {
			echo p('Error! Debug time was less than 0 seconds:') . ul(array(
				"Dsec: $dsec",
				"Start: $this->StartTime, $t1",
				"Stop: $this->StopTime, $t2"
			));
		}
		return $result;
	}

	/*!
	Prints the benchmark results.
	*/
	function results()
	{
		$elapsed = $this->elapsed();
		return p('Time elapsed: ' . number_format($elapsed,3).' seconds');
	}

	/*!
	Prints the benchmark results.
	*/
	function printResults()
	{
		print $this->results();
	}

	/* Stops and prints the benchmark */
	function end() {
		$this->stop();
		$this->printResults();
	}


	var $StartTime;
	var $StopTime;
}

?>