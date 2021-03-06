<?php
/**
 * PHP version 5
 *
 * @package    generalDriver
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\DcGeneral\Contao\Twig;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\IdSerializer;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;

/**
 * Extension for twig template engine.
 */
class DcGeneralExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dc-general';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('serializeModelId', array($this, 'serializeModelId')),
        );
    }

    /**
     * Serialize a model and return its ID.
     *
     * @param ModelInterface $model The model.
     *
     * @return string
     */
    public function serializeModelId(ModelInterface $model)
    {
        $serializer = IdSerializer::fromModel($model);
        return $serializer->getSerialized();
    }
}
