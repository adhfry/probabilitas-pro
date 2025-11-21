<script setup>
import { computed } from 'vue';

const props = defineProps({
    formula: {
        type: String,
        required: true
    },
    inline: {
        type: Boolean,
        default: false
    }
});

// Parse simple LaTeX-like syntax to HTML
const parsedFormula = computed(() => {
    let html = props.formula;
    
    // First, handle fractions before other replacements
    // \frac{a}{b} -> proper fraction display
    html = html.replace(/\\frac\{([^}]+)\}\{([^}]+)\}/g, '<span class="frac"><span class="frac-num">$1</span><span class="frac-den">$2</span></span>');
    
    // Handle \prod and \sum with subscript and superscript limits
    // Must be done BEFORE general symbol replacement
    // Matches: \prod_{i=1}^{n} or \sum_{i=1}^{n}
    // Don't wrap in span to avoid blocking further processing
    html = html.replace(/\\prod_\{([^}]+)\}\^\{([^}]+)\}/g, '∏<sub>$1</sub><sup>$2</sup>');
    html = html.replace(/\\sum_\{([^}]+)\}\^\{([^}]+)\}/g, '∑<sub>$1</sub><sup>$2</sup>');
    
    // Handle \prod and \sum with only subscript (no superscript)
    html = html.replace(/\\prod_\{([^}]+)\}/g, '∏<sub>$1</sub>');
    html = html.replace(/\\sum_\{([^}]+)\}/g, '∑<sub>$1</sub>');
    
    // Handle general subscripts (must be before symbol replacements)
    // Matches: X_{abc} or X_i or C_k
    html = html.replace(/([A-Za-z0-9]+)_\{([^}]+)\}/g, '$1<sub>$2</sub>');
    html = html.replace(/([A-Za-z0-9]+)_([A-Za-z0-9]+)/g, '$1<sub>$2</sub>');
    
    // Handle general superscripts
    // Matches: X^{2} or X^2
    html = html.replace(/([A-Za-z0-9]+)\^\{([^}]+)\}/g, '$1<sup>$2</sup>');
    html = html.replace(/([A-Za-z0-9]+)\^([A-Za-z0-9]+)/g, '$1<sup>$2</sup>');
    
    // Replace common math symbols (AFTER subscript/superscript handling)
    html = html.replace(/\\times/g, '×');
    html = html.replace(/\\cdot/g, '·');
    html = html.replace(/\\div/g, '÷');
    html = html.replace(/\\pm/g, '±');
    html = html.replace(/\\approx/g, '≈');
    html = html.replace(/\\propto/g, '∝');
    // Note: \\sum and \\prod already handled above with subscripts
    html = html.replace(/\\sum/g, '∑'); // Catch any remaining standalone
    html = html.replace(/\\prod/g, '∏'); // Catch any remaining standalone
    html = html.replace(/\\int/g, '∫');
    html = html.replace(/\\infty/g, '∞');
    html = html.replace(/\\le/g, '≤');
    html = html.replace(/\\ge/g, '≥');
    html = html.replace(/\\ne/g, '≠');
    html = html.replace(/\\in/g, '∈');
    html = html.replace(/\\subset/g, '⊂');
    html = html.replace(/\\cap/g, '∩');
    html = html.replace(/\\cup/g, '∪');
    
    // Greek letters
    html = html.replace(/\\alpha/g, 'α');
    html = html.replace(/\\beta/g, 'β');
    html = html.replace(/\\gamma/g, 'γ');
    html = html.replace(/\\delta/g, 'δ');
    html = html.replace(/\\theta/g, 'θ');
    html = html.replace(/\\pi/g, 'π');
    html = html.replace(/\\sigma/g, 'σ');
    html = html.replace(/\\mu/g, 'μ');
    
    // Parentheses
    html = html.replace(/\\left\(/g, '(');
    html = html.replace(/\\right\)/g, ')');
    html = html.replace(/\\left\[/g, '[');
    html = html.replace(/\\right\]/g, ']');
    
    // Scientific notation: 1.23e-4 -> 1.23×10^(-4)
    html = html.replace(/(\d+\.?\d*)e([+-]?\d+)/g, (match, mantissa, exponent) => {
        return `${mantissa}×10<sup>${exponent}</sup>`;
    });
    
    return html;
});
</script>

<template>
    <span 
        :class="['math-formula', { 'inline': inline, 'block': !inline }]"
        v-html="parsedFormula"
    ></span>
</template>

<style scoped>
.math-formula {
    font-family: 'Times New Roman', 'Latin Modern Math', 'STIX Two Math', serif;
    font-size: 1.1em;
    line-height: 1.6;
}

.math-formula.inline {
    display: inline;
}

.math-formula.block {
    display: block;
    margin: 0.5em 0;
    text-align: center;
}

/* Fractions */
.frac {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    vertical-align: middle;
    margin: 0 0.2em;
    font-size: 0.9em;
}

.frac-num {
    border-bottom: 1.5px solid currentColor;
    padding: 0.1em 0.3em;
    line-height: 1.1;
    min-width: 100%;
    text-align: center;
}

.frac-den {
    padding: 0.1em 0.3em;
    line-height: 1.1;
    min-width: 100%;
    text-align: center;
}

/* Math operators with limits (sum, product) */
.math-op {
    display: inline-block;
    position: relative;
    font-size: 1.4em;
    vertical-align: middle;
    margin: 0 0.2em;
}

/* Subscripts and superscripts */
:deep(sub) {
    font-size: 0.75em;
    vertical-align: sub;
}

:deep(sup) {
    font-size: 0.75em;
    vertical-align: super;
}
</style>
