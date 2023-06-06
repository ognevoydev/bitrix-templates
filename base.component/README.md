# base.component

Базовый шаблон для компонента. По умолчанию выводит элементы инфоблока в таблице.

## Использование

1. Поместить папку `base.component/` в `local/components/namespace`

2. Внедрить компонент на странице, используя конструкцию:

```php
<?$APPLICATION->IncludeComponent(
	"namespace:base.component",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "articles",
		"SORT_BY1" => "ID",
		"SORT_ORDER1" => "DESC"
	)
);?>
```
