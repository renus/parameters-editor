# parameters-editor
Symfony 2 bundle to edit parameters.yml symfony 2 or symfony 3 file

To use RenusParametersEditorBundle in your project add it via composer

# Installation

## installation
    
1. Add this bundle to your project with composer:
    
<pre>
composer.phar require renus/parameters-editor:1.0.x-dev
</pre>


2. Register the bundle


```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new \Renus\ParametersEditorBundle\RenusParametersEditorBundle(),
    );
    // ...
}

#configuration 

##application twitter

first you must create an twitter application  on https://apps.twitter.com/ ,and create a token (read permission)


##parameters

### 1.config file
you can configure the bundle parameters in app/config/config.yml, the  parameters can be added like this :

```yml
# app/config/config.yml
renus_parameters_editor:
    all_parameters: false 
    default_keyword: editable
```

+ **all_parameters**: it's a boolean (default false) when it is true, you can see all the parameters in parameters.yml file else you see
 only the parameters who are prefixed by the "keyword" (default: editable, ex: editable.email).

+ **default_keyword**: the keyword who prefix the paramters who can be edit (default: editable)

### 2. routing file

```yml
# app/config/routing.yml
renus_parameters_editor:
    resource: "@RenusParametersEditorBundle/Resources/config/routing.yml"
    prefix:   /administration/config/editor/
```

**You must protect this url with your firewall**

#Usage

##twig usage: 

just add this render command in your twig template  :
<pre>
{% render(path('renus_parameters_editor_homepage')) %}
</pre>
