Testing how Espresso style matchers might work with WebDriver as an alternative to awkward contains and sibling Xpath
expressions.

```php
$espresso = usingDriver($driver, new EspressoOptions(
    matchProcessor: new RetryingMatchProcessor(
        waitTimeoutInSeconds: 5,
        waitIntervalInMilliseconds: 250,
    ),
));

$espresso->onElement(withLabel('First name'))
    ->perform(typeText('John'));

$espresso->onElement(withLabel('Last name'))
    ->perform(
        typeText('Doe'),
        submit(),
    );

$espresso->onElement(withClass('status-message'))
    ->check(matches(withTextContaining('User created.')));
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

---

<!-- TOC -->
* [Helper functions](#helper-functions)
  * [Core](#core)
    * [usingDriver(`WebDriver`, `EspressoOptions`)](#usingdriverwebdriver-espressooptions)
  * [Matchers](#matchers)
    * [allOf(`MatcherInterface ...`)](#allofmatcherinterface-)
    * [anyOf(`MatcherInterface ...`)](#anyofmatcherinterface-)
    * [hasDescendant(`MatcherInterface`)](#hasdescendantmatcherinterface)
    * [hasFocus()](#hasfocus)
    * [hasSibling(`MatcherInterface`)](#hassiblingmatcherinterface)
    * [isChecked()](#ischecked)
    * [isDisplayed()](#isdisplayed)
    * [isDisplayedInViewport()](#isdisplayedinviewport)
    * [isEnabled()](#isenabled)
    * [isPresent()](#ispresent)
    * [not(`MatcherInterface`)](#notmatcherinterface)
    * [withClass(`string`)](#withclassstring)
    * [withId(`string`)](#withidstring)
    * [withLabel(`string`)](#withlabelstring)
    * [withTagName(`string`)](#withtagnamestring)
    * [withText(`string`)](#withtextstring)
    * [withTextContaining(`string`)](#withtextcontainingstring)
    * [withValue(`string`)](#withvaluestring)
  * [Assertions](#assertions)
    * [doesNotExist()](#doesnotexist)
    * [matches(`MatcherInterface`)](#matchesmatcherinterface)
  * [Actions](#actions)
    * [clearText()](#cleartext)
    * [click()](#click)
    * [focus()](#focus)
    * [scrollTo()](#scrollto)
    * [sendKeys(`string ...`)](#sendkeysstring-)
    * [submit()](#submit)
    * [typeText(`string`)](#typetextstring)
* [Interfaces](#interfaces)
  * [EspressoCore](#espressocore)
    * [navigateTo(`string`): `EspressoCore`](#navigatetostring-espressocore)
    * [inContainer(`MatcherInterface`): `EspressoCore`](#incontainermatcherinterface-espressocore)
    * [onElement(`MatcherInterface`): `InteractionInterface`](#onelementmatcherinterface-interactioninterface)
  * [InteractionInterface](#interactioninterface)
    * [check(`AssertionInterface`): `InteractionInterface`](#checkassertioninterface-interactioninterface)
    * [perform(`ActionInterface ...`): `InteractionInterface`](#performactioninterface--interactioninterface)
<!-- TOC -->


# Helper functions

These can be imported from the `EspressoWebDriver` namespace.


## Core

Core helpers return `EspressoCore` instances. They are the entry point for the library.

### usingDriver(`WebDriver`, `EspressoOptions`)

Returns a new [EspressoCore](#espressocore) instance with the specified driver and options.


## Matchers

Matchers return `MatcherInterface` instances. They are used with the [EspressoCore](#espressocore) methods, other
matchers to further refine the selection of elements, and assertions to check the state of elements.

### allOf(`MatcherInterface ...`)

Matches elements that match all the given matchers.

### anyOf(`MatcherInterface ...`)

Matches elements that match any of the given matchers.

### hasDescendant(`MatcherInterface`)

Matches elements that have a descendant that matches the given matcher.

### hasFocus()

Matches elements that have focus.

### hasSibling(`MatcherInterface`)

Matches elements that have a sibling that matches the given matcher.

### isChecked()

Matches elements that are checked.

### isDisplayed()

Matches elements that are visible, but not necessarily within the viewport.

### isDisplayedInViewport()

Matches elements that are visible and within the viewport.

### isEnabled()

Matches elements that are enabled.

### isPresent()

Matches elements that are present in the DOM.

### not(`MatcherInterface`)

Matches elements that do not match the given matcher.

### withClass(`string`)

Matches elements that have the given class.

### withId(`string`)

Matches elements that have the given id.

### withLabel(`string`)

Matches form elements that have the given label.

### withTagName(`string`)

Matches elements that have the given tag name.

### withText(`string`)

Matches elements that have the given text using an exact match.

### withTextContaining(`string`)

Matches elements that have the given text using a partial case-insensitive match.

### withValue(`string`)

Matches form elements that have the given value.


## Assertions

Assertions return `AssertionInterface` instances. They are used with the `check` method of
[InteractionInterface](#interactioninterface).

### doesNotExist()

Asserts that the element does not exist.

### matches(`MatcherInterface`)

Asserts that the element matches the given matcher.


## Actions

Actions return `ActionInterface` instances. They are used with the `perform` method of
[InteractionInterface](#interactioninterface).

### clearText()

Clears the text from an input or textarea element.

### click()

Clicks an element.

### focus()

Focuses an element.

### scrollTo()

Scrolls to an element.

### sendKeys(`string ...`)

Sends keys to an element.

See WebDriverKeys in [php-webdriver](https://github.com/php-webdriver/php-webdriver/).

### submit()

Submits a form, can be called on child elements of a form.

### typeText(`string`)

Selects, then types text into an element.


# Interfaces


## EspressoCore

### navigateTo(`string`): `EspressoCore`

Navigates to the given URL.

### inContainer(`MatcherInterface`): `EspressoCore`

Returns a new `EspressoCore` instance with the container set to the given matcher. Any matches or actions will be
constrained to the container.

### onElement(`MatcherInterface`): `InteractionInterface`

Returns a new `InteractionInterface` instance with the element set to the given matcher.


## InteractionInterface

### check(`AssertionInterface`): `InteractionInterface`

Checks that the element matches the given assertion.

### perform(`ActionInterface ...`): `InteractionInterface`

Performs the given action(s) on the element.
