Testing how Espresso style matchers might work with WebDriver as an alternative to awkward contains and sibling Xpath expressions.

```php
withDriver($driver)
    ->onElement(allOf(
        withTagName('input'),
        hasSibling(allOf(withTagName('label'), withText('First name'))),
    ))
    ->perform(click())
    ->check(matches(hasFocus()))
    ->perform(typeText('Name'), submit());
```
