Testing how Espresso style matchers might work with WebDriver as an alternative to awkward contains and sibling Xpath
expressions.

```php
$espresso = usingDriver($driver);

$espresso->onElement(withLabel('First name')),
    ->perform(typeText('John'));

$espresso->onElement(withLabel('Last name')),
    ->perform(
        typeText('Doe'),
        submit(),
    );
```

```php
$containedEspresso = usingDriver($driver)
    ->inContainer(allOf(
        withClass('row'),
        hasDescendant(withText('John Doe')),
    ));

$containedEspresso->onElement(withText('Edit'))
    ->perform(click());
```
