![tests](https://github.com/jeyroik/extas-installer-plugins/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-installer-plugins/coverage.svg?branch=master)
<a href="https://codeclimate.com/github/jeyroik/extas-installer-plugins/maintainability"><img src="https://api.codeclimate.com/v1/badges/347a8c427a8eef65b4b3/maintainability" /></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-installer-plugins/v)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-installer-plugins/downloads)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Dependents](https://poser.pugx.org/jeyroik/extas-installer-plugins/dependents)](//packagist.org/packages/jeyroik/extas-jsonrpc)


# Описание

Пакет предоставляет механизм описания плагинов для установки/удаления сущностей, что позволяет избежать лишнего кода.

# Использование

Раньше, если требовалось реализовать поддержку установки и удаления сущности, требовалось реализовать два плагина - для стадии установки и для стадии удаления.

Теперь достаточно описать сущность:

extas.json

```json
{
  "plugins_install": [
    {
      "repository": "extas\\components\\my\\Repository",
      "name": "my entity",
      "section": "my_entities"
    } 
  ]
}
```

Это создаст два плагина для установки сущностей из секции `my_entities`.

Комбинируя данный пакет с `extas-repositories` можно получить следующий результат:

```json
{
  "repositories": [
      {
        "name": "my_repo",
        "scope": "extas",
        "pk": "name",
        "class": "extas\\components\\my\\Item",
        "aliases": ["myRepo"]
      }
    ],
  "plugins_install": [
    {
      "repository": "myRepo",
      "name": "my entity",
      "section": "my_entities"
    } 
  ]
}
```

Кроме того, если имя сущности совпадает с именем секции, то секцию можно опустить:

```json
{
  "plugins_install": [
    {
      "repository": "myRepo",
      "name": "entities",
    } 
  ]
}
```

Вместе со всем этим, для дополнительного контроля и гибкости, существует стадия `extas.plugin.install.construct`, которая позволяет подключиться к стадии формирования плагина и собрать его по логике необходимой вам. Детали стадии см. в `src/interfaces/stages/IStagePluginInstallConstruct`.