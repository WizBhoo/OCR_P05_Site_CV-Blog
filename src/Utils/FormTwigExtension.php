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
     * @param array       $context Context de la vue Twig
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
     * Generate HTML following errors in context
     *
     * @param $context
     * @param $key
     *
     * @return string
     */
    protected function getErrorHTML($context, $key)
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
     * @param $value
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
    protected function getHtmlFromArray(array $attributes)
    {
        return implode(
            ' ',
            array_map(
                function ($key, $value) {
                    return "$key=\"$value\"";
                },
                array_keys($attributes),
                $attributes
            )
        );
    }
}
