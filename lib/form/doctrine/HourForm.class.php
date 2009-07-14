<?php

/**
 * Hour form.
 *
 * @package    form
 * @subpackage Hour
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class HourForm extends BaseHourForm
{
  public function configure()
  {
		unset(
			$this['created_at'], $this['updated_at']
		);
  }
}