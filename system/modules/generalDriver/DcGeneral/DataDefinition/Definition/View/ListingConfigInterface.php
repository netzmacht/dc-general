<?php
/**
 * PHP version 5
 * @package    generalDriver
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace DcGeneral\DataDefinition\Definition\View;

interface ListingConfigInterface
{
	/**
	 * Do not group.
	 */
	const GROUP_NONE = 'none';

	/**
	 * Group by characters, the max char count depend on the mode length
	 * (which is 1 by default for char grouping).
	 */
	const GROUP_CHAR = 'char';

	/**
	 * Group by digits, the max digit count depend on the mode length
	 * (which is infinity by default for digit grouping)..
	 */
	const GROUP_DIGIT = 'digit';

	/**
	 * Sort by day from datetime property.
	 */
	const GROUP_DAY = 'day';

	/**
	 * Sort by week day from datetime property.
	 */
	const GROUP_WEEKDAY = 'weekday';

	/**
	 * Sort by week of the year from datetime property.
	 */
	const GROUP_WEEK = 'week';

	/**
	 * Sort by month from datetime property.
	 */
	const GROUP_MONTH = 'month';

	/**
	 * Sort by year from datetime property.
	 */
	const GROUP_YEAR = 'year';

	const SORT_ASC = 'asc';

	const SORT_DESC = 'desc';

	const SORT_RANDOM = 'random';

	/**
	 * Set the grouping mode.
	 *
	 * @param string $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setGroupingMode($value);

	/**
	 * Return the grouping mode.
	 *
	 * @return string
	 */
	public function getGroupingMode();

	/**
	 * Set the grouping length.
	 *
	 * @param int $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setGroupingLength($value);

	/**
	 * The grouping length is used for char or digit grouping and define
	 * how many chars or digits should be respected when group.
	 *
	 * @return int
	 */
	public function getGroupingLength();

	/**
	 * Set the list sorting mode.
	 *
	 * @param string $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setSortingMode($value);

	/**
	 * Return the list sorting mode.
	 * This sorting is applied after grouping and could also be called "in-group sorting".
	 *
	 * @return string
	 */
	public function getSortingMode();

	/**
	 * Set the default sorting fields.
	 *
	 * @param array $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setDefaultSortingFields($value);

	/**
	 * Get the default sorting fields which are used if the user does not define a sorting.
	 *
	 * @return array
	 */
	public function getDefaultSortingFields();

	/**
	 * Set the list of parent's model property names.
	 *
	 * @param array $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setHeaderPropertyNames($value);

	/**
	 * Return a list of parent's model property names, which are shown above the item list.
	 *
	 * @return array
	 */
	public function getHeaderPropertyNames();

	/**
	 * Set the icon path to the root item's icon.
	 *
	 * @param  $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setRootIcon($value);

	/**
	 * Return the icon path to the root item's icon.
	 *
	 * @return string
	 */
	public function getRootIcon();

	/**
	 * Set the css classes that should be added to the items container.
	 *
	 * @param string $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setItemCssClass($value);

	/**
	 * Return css classes that should be added to the items container.
	 *
	 * @return string
	 */
	public function getItemCssClass();

	/**
	 * Set the label formatter.
	 *
	 * @param string                        $providerName
	 *
	 * @param ModelFormatterConfigInterface $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setLabelFormatter($providerName, $value);

	/**
	 * Return the label formatter.
	 *
	 * @param string $providerName
	 *
	 * @return ModelFormatterConfigInterface
	 */
	public function getLabelFormatter($providerName);

	/**
	 * Set if the listing shall be in table columns.
	 *
	 * @param bool $value
	 *
	 * @return ListingConfigInterface
	 */
	public function setShowColumns($value);

	/**
	 * Get if the listing shall be in table columns.
	 *
	 * @return bool
	 */
	public function getShowColumns();
}
