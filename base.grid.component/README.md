# base.grid.component

Пример компонента с использованием main.ui.grid и main.ui.filter.

## Использование

1. Поместить папку `base.grid.component/` в `local/components/namespace`

2. Внедрить компонент на странице, используя конструкцию:

```php
<?$APPLICATION->IncludeComponent(
	"namespace:base.grid.component",
	"",
	Array(
	    "USER_ID" => 1,
		"CACHE_TIME" => "36000000",
	)
);?>
```
