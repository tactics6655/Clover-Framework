<?php

class ActivationFunctions
{

	public function log($v, $base = M_E)
	{
		return log($v, $base);
	}

	public function erf()
	{
	}

	public function derivative()
	{
	}

	public function partialDerivative()
	{
	}

	public function backPropagtion()
	{
	}

	public function crossEntropy()
	{
	}

	public function softmax(array $v)
	{
		$v = array_map('exp', array_map('floatval', $v));
		$sum = array_sum($v);

		foreach ($v as $index => $value) {
			$v[$index] = $value / $sum;
		}

		return $v;
	}

	public function sigmoid($x)
	{
		return 1 / (1 + exp(-$x));
	}

	public function sigmoidDerivative($x)
	{
		return $x * (1 - $x);
	}

	public function tanh($x)
	{
		if (function_exists('tanh')) {
			return tanh($x);
		}

		return (exp($x) - exp(-$x)) / (exp($x) + exp(-$x));
	}

	// Scaled Exponential Linear Unit
	public function selu($x)
	{
	}

	// Exponential Linear Unit
	public function elu($x)
	{
	}

	public function maxout()
	{
	}

	// Sigmoid-Weighted Linear Unit
	public function swish($x)
	{
	}


	// Rectified Linear Unit
	public function relu($x)
	{
	}

	// Gaussian Error Linear Unit
	public function gelu($x)
	{
	}
}
