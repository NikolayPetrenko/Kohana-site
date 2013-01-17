<?php defined('SYSPATH') or die('No direct script access.');

require_once Kohana::find_file('vendor', 'stripe-php-1.6.5/lib/Stripe');

/**
 * Image manipulation support. Allows images to be resized, cropped, etc.
 *
 * @package    Kohana/Image
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Stripe extends Stripe_Vendor {}