// Credit: https://usehooks-ts.com/
import { useCallback, useState } from 'react';

type CopiedValue = string | null;

type CopyFn = (text: string) => Promise<boolean>;

export function useClipboard(): [CopiedValue, CopyFn] {
    const [copiedText, setCopiedText] = useState<CopiedValue>(null);

    const copy: CopyFn = useCallback(async (text) => {
        if (navigator?.clipboard && window.isSecureContext) {
            try {
                await navigator.clipboard.writeText(text);
                setCopiedText(text);
                setTimeout(() => setCopiedText(null), 2000);
                return true;
            } catch (error) {
                console.warn('Clipboard API failed, trying fallback:', error);
            }
        } else {
            console.warn('Clipboard API not available or not in secure context');
        }

        try {
            const textArea = document.createElement('textarea');
            textArea.value = text;

            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            textArea.style.opacity = '0';
            textArea.style.pointerEvents = 'none';
            textArea.style.zIndex = '-1';
            textArea.setAttribute('readonly', '');
            textArea.setAttribute('aria-hidden', 'true');

            document.body.appendChild(textArea);

            textArea.focus();
            textArea.select();
            textArea.setSelectionRange(0, 99999);

            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);


            if (successful) {
                setCopiedText(text);
                setTimeout(() => setCopiedText(null), 2000);
                return true;
            }
        } catch (error) {
            console.warn('execCommand fallback failed', error);
        }

        try {
            const range = document.createRange();
            const selection = window.getSelection();

            const span = document.createElement('span');
            span.textContent = text;
            span.style.position = 'fixed';
            span.style.left = '-999999px';
            span.style.top = '-999999px';

            document.body.appendChild(span);
            range.selectNode(span);
            selection?.removeAllRanges();
            selection?.addRange(range);

            const successful = document.execCommand('copy');

            selection?.removeAllRanges();
            document.body.removeChild(span);


            if (successful) {
                setCopiedText(text);
                setTimeout(() => setCopiedText(null), 2000);
                return true;
            }
        } catch (error) {
            console.warn('Selection fallback failed', error);
        }

        console.error('All copy methods failed');
        return false;
    }, []);

    return [copiedText, copy];
}
