LoadClassMetaData -> change the repositoryclass

#EntityManager
1)$this->getDoctrine()->getManagerClass(User:class)->getRepository(User:clas); ** use specific entity manager
2)$this>getDoctrine()->getRepository(User:class); -> ** use general entitymanager

#Doctrine helper from ORO

ORO related metadata @Config on Entity Level
ORO related metadata @ConfigField on Entity Fields/Columns

#dataaudit = true will enable history on the entity levels per columns

# How to change entity via Migration Scripts


###Adding custom field on existing Entities - > Extendend Entity


##GenerateExtension  


$$$ buildAsscociateName

### ExtendDbIdentifierNameGenerator -> genarating keys in migration scripts

### Extension generators:
    oro_activity_list.entity_generator.extension:
        class: '%oro_activity_list.entity_generator.extension.class%'
        arguments:
            - "@oro_activity_list.provider.chain"
        tags:
            - { name: oro_entity_extend.entity_generator_extension }
            
#### Datagrid without orm -> frontend-product-search-grid
OnbuildAFter or grid events are being used in the frontend to add prices etc.

### oro_datagrid.extension         


###Navigation


###Validation
- SF validation constrains   



### Locating Resources:

