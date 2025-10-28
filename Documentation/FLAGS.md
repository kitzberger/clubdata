# Multipurpose flags

The program model features a multipurpose field `flags` that is a checkbox without any options.

But you can define them yourself.

## Add options via PageTS

```
TCEFORM.tx_clubdata_domain_model_program {
  flags {
    disabled = 0

    # value keys have be single bits (of a bitmask)
    addItems.1 = Warning
    addItems.2 = Danger
    addItems.4 = Fatal
  }
}
```

## Use flags in templates

```xml
<f:if condition="{club:bitwiseOr(number:program.flags,bit:1)}">
    <div class="alert alert-warning">
        This is the 1st bit of our flags ("one") speaking!
    </div>
</f:if>
<f:if condition="{club:bitwiseOr(number:program.flags,bit:2)}">
    <div class="alert alert-danger">
        This is the 2nd bit of our flags ("two") speaking!
    </div>
</f:if>
<f:if condition="{club:bitwiseOr(number:program.flags,bit:4)}">
    <div class="alert alert-danger">
        This is the 3rd bit of our flags ("four") speaking!
    </div>
</f:if>
```
