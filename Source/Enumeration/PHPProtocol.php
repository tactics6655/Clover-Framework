<?php

namespace Clover\Enumeration;

abstract class PHPProtocol
{

	public const STANDARD_ERROR = "php://stderr";

	public const STANDARD_OUTPUT = "php://stdout";

	public const STANDARD_INPUT = "php://stdin";

	public const FILTER = "php://filter";

	public const TEMPORARY = "php://temp";

	public const MEMORY = "php://memory";

	public const INPUT = "php://input";

	public const OUTPUT = "php://output";
}
