<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FormTwigExtension.
 */
class FormTwigExtension extends AbstractExtension
{
    /**
     * FormTwigExtension getFunction.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'field',
                [$this, 'field'],
                ['is_safe' => ['html'], 'needs_context' => true]
            ),
        ];
    }

    /**
     * Generate field's HTML code
     *
     * @param array       $context Twig view context
     * @param string      $key     Field's Key
     * @param mixed       $value   Filed's value
     * @param string|null $label   Label to use
     * @param array       $options
     *
     * @return string
     */
    public function field(array $context, string $key, $value, ?string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $disabled = $options['disabled'] ?? '';
        $error = $this->getErrorHTML($context, $key);
        if (!is_null($value)) {
            $value = $this->convertValue($value);
        }
        $attributes = [
            'class' => 'form-control',
            'name' => $key,
            'id' => $key,
        ];

        if ($error) {
            $attributes['class'] .= ' is-invalid form-control';
        }
        if ('textarea' === $type) {
            $input = $this->textarea($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes, $disabled);
        }

        return "<div class=\"row form-group\">
                    <div class=\"col col-md-3\">
                        <label for=\"{$key}\" class=\"form-control-label\">
                            {$label}
                        </label>
                    </div>
                    <div class=\"col-12 col-md-9\">
                        {$input}
                        {$error}
                    </div>
                </div>";
    }

    /**
     * Generate an input
     *
     * @param string|null $value
     * @param array       $attributes
     * @param string      $disabled
     *
     * @return string
     */
    protected function input(?string $value, array $attributes, string $disabled): string
    {
        return sprintf(
            "<input type=\"text\" %s value=\"{$value}\" {$disabled}>",
            $this->getHtmlFromArray($attributes)
        );
    }

    /**
     * Generate a textarea
     *
     * @param string|null $value
     * @param array       $attributes
     *
     * @return string
     */
    protected function textarea(?string $value, array $attributes): string
    {
        return sprintf(
            "<textarea %s rows=\"6\">{$value}</textarea>",
            $this->getHtmlFromArray($attributes)
        );
    }

    /**
     * Generate a select
     *
     * @param string|null $value
     * @param array       $options
     * @param array       $attributes
     *
     * @return string
     */
    protected function select(?string $value, array $options, array $attributes): string
    {
        $htmlOptions = array_reduce(
            array_keys($options),
            function (string $html, string $key) use ($options, $value) {
                $params = ['value' => $key, 'selected' => $key === $value];

                return sprintf(
                    "%s<option %s>%s</option>",
                    $html,
                    $this->getHtmlFromArray($params),
                    $options[$key]
                );
            },
            ""
        );

        return sprintf(
            "<select %s>$htmlOptions</select>",
            $this->getHtmlFromArray($attributes)
        );
    }

    /**
     * Generate HTML following errors in context
     *
     * @param $context
     * @param $key
     *
     * @return string
     */
    protected function getErrorHTML($context, $key): string
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }

        return "";
    }

    /**
     * Verify if value has to be converted
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function convertValue($value): string
    {
        if ($value instanceof DateTime) {
            return $value->format('d/m/Y H:i');
        }

        return $value;
    }

    /**
     * Transform a table $key => $value in HTML attributes
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function getHtmlFromArray(array $attributes): string
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if (true === $value) {
                $htmlParts[] = (string) $key;
            } elseif (false !== $value) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }

        return implode(' ', $htmlParts);
    }
}
