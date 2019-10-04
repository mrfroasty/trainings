<?php


namespace Training\Bundle\UserNamingBundle\ImportExport\Configuration;

use Oro\Bundle\ImportExportBundle\Configuration\ImportExportConfiguration;
use Oro\Bundle\ImportExportBundle\Configuration\ImportExportConfigurationInterface;
use Oro\Bundle\ImportExportBundle\Configuration\ImportExportConfigurationProviderInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class UserNamingTypeImportExportConfigurationProvider implements ImportExportConfigurationProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): ImportExportConfigurationInterface
    {
        return new ImportExportConfiguration([
            ImportExportConfiguration::FIELD_ENTITY_CLASS => UserNamingType::class,
            ImportExportConfiguration::FIELD_EXPORT_PROCESSOR_ALIAS => 'training_user_naming_type',
            ImportExportConfiguration::FIELD_EXPORT_TEMPLATE_PROCESSOR_ALIAS => 'training_user_naming_type',
            ImportExportConfiguration::FIELD_IMPORT_PROCESSOR_ALIAS => 'training_user_naming_type.add_or_replace',
            ImportExportConfiguration::FIELD_IMPORT_STRATEGY_TOOLTIP =>
                $this->translator->trans('training.usernaming.import.strategy.tooltip'),
        ]);
    }

}