<?php
/**
 * PHP version 5
 *
 * @package    generalDriver
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\DcGeneral;

use ContaoCommunityAlliance\DcGeneral\Contao\DataDefinition\Definition\Contao2BackendViewDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\IdSerializer;
use ContaoCommunityAlliance\DcGeneral\Data\ConfigInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\BasicDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\Exception\DcGeneralRuntimeException;

/**
 * Registry for default data provider configurations to only resolve them once.
 */
class BaseConfigRegistry implements BaseConfigRegistryInterface
{
    /**
     * The attached environment.
     *
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * The cached configurations.
     *
     * @var ConfigInterface[]
     */
    private $configs;

    /**
     * Set the environment to use.
     *
     * @param EnvironmentInterface $environment The environment.
     *
     * @return BaseConfigRegistry
     */
    public function setEnvironment(EnvironmentInterface $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Add the filter for the item with the given id from the parent data provider to the given config.
     *
     * @param IdSerializer    $idParent The id of the parent item.
     *
     * @param ConfigInterface $config   The config to add the filter to.
     *
     * @return ConfigInterface
     *
     * @throws DcGeneralRuntimeException When the parent item is not found.
     */
    private function addParentFilter($idParent, $config)
    {
        $environment        = $this->getEnvironment();
        $definition         = $environment->getDataDefinition();
        $providerName       = $definition->getBasicDefinition()->getDataProvider();
        $parentProviderName = $idParent->getDataProviderName();
        $parentProvider     = $environment->getDataProvider($parentProviderName);

        if ($definition->getBasicDefinition()->getParentDataProvider() !== $parentProviderName) {
            throw new DcGeneralRuntimeException(
                'Unexpected parent provider ' . $parentProviderName .
                ' (expected ' . $definition->getBasicDefinition()->getParentDataProvider() . ')'
            );
        }

        if ($parentProvider) {
            $objParent = $parentProvider->fetch($parentProvider->getEmptyConfig()->setId($idParent->getId()));
            if (!$objParent) {
                throw new DcGeneralRuntimeException(
                    'Parent item ' . $idParent->getSerialized() . ' not found in ' . $parentProviderName
                );
            }

            $condition = $definition->getModelRelationshipDefinition()->getChildCondition(
                $parentProviderName,
                $providerName
            );

            if ($condition) {
                $arrBaseFilter = $config->getFilter();
                $arrFilter     = $condition->getFilter($objParent);

                if ($arrBaseFilter) {
                    $arrFilter = array_merge($arrBaseFilter, $arrFilter);
                }

                $config->setFilter(
                    array(
                        array(
                            'operation' => 'AND',
                            'children'  => $arrFilter,
                        )
                    )
                );
            }
        }

        return $config;
    }

    /**
     * Retrieve the base data provider config for the current data definition.
     *
     * This includes parent filter when in parented list mode and the additional filters from the data definition.
     *
     * @param IdSerializer|null $parentId The parent to use.
     *
     * @return ConfigInterface
     */
    private function buildBaseConfig($parentId)
    {
        $environment = $this->getEnvironment();
        $config      = $environment->getDataProvider()->getEmptyConfig();
        $definition  = $environment->getDataDefinition();
        $additional  = $definition->getBasicDefinition()->getAdditionalFilter();

        // Custom filter common for all modes.
        if ($additional) {
            $config->setFilter($additional);
        }

        if (!$config->getSorting()) {
            /** @var Contao2BackendViewDefinitionInterface $viewDefinition */
            $viewDefinition = $definition->getDefinition(Contao2BackendViewDefinitionInterface::NAME);
            $config->setSorting($viewDefinition->getListingConfig()->getDefaultSortingFields());
        }

        // Special filter for certain modes.
        if ($parentId) {
            $this->addParentFilter($parentId, $config);
        } elseif ($definition->getBasicDefinition()->getMode() == BasicDefinitionInterface::MODE_PARENTEDLIST) {
            $pid        = $environment->getInputProvider()->getParameter('pid');
            $pidDetails = IdSerializer::fromSerialized($pid);

            $this->addParentFilter($pidDetails, $config);
        }

        return $config;
    }

    /**
     * Retrieve the base data provider config for the current data definition.
     *
     * This includes parent filter when in parented list mode and the additional filters from the data definition.
     *
     * @param IdSerializer $parentId The optional parent to use.
     *
     * @return ConfigInterface
     */
    public function getBaseConfig(IdSerializer $parentId = null)
    {
        $key = $parentId ? $parentId->getSerialized() : null;

        if (!isset($this->configs[$key])) {
            $this->configs[$key] = $this->buildBaseConfig($parentId);
        }

        return clone $this->configs[$key];
    }
}
