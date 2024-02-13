Testing how Espresso style matchers might work with WebDriver as an alternative to awkward contains and sibling Xpath expressions.

```php
$espresso = usingDriver($driver);

$espresso
    ->onElement(allOf(
        withTagName('input'),
        hasSibling(allOf(withTagName('label'), withText('First name'))),
    ))
    ->perform(click(), typeText('John'));

$espresso
    ->onElement(allOf(
        withTagName('input'),
        hasSibling(allOf(withTagName('label'), withText('Last name'))),
    ))
    ->perform(click(), typeText('Doe'), submit());
```

```php
$containedEspresso = usingDriver($driver)
    ->inContainer(allOf(
        withClass('row'),
        hasDescendant(withText('John Doe')),
    ));

$containedEspresso
    ->onElement(withText('Edit'))
    ->perform(click());
```
