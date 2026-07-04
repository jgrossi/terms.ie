import Alpine from 'alpinejs'
import htmx from 'htmx.org'

window.Alpine = Alpine
window.htmx = htmx

Alpine.data('toastStack', (initial = null) => ({
    toasts: [],
    init() {
        if (initial) this.add(initial.message, initial.type)
    },
    add(message, type = 'success') {
        const id = Date.now() + Math.random()
        this.toasts.push({ id, message, type })
        setTimeout(() => this.remove(id), 4000)
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id)
    },
}))

document.addEventListener('toast', e => {
    window.dispatchEvent(new CustomEvent('toast-push', { detail: e.detail }))
})

Alpine.data('copyButton', (text) => ({
    copied: false,
    async copy() {
        try {
            await navigator.clipboard.writeText(text)
        } catch {
            const ta = document.createElement('textarea')
            ta.value = text
            ta.style.position = 'fixed'
            ta.style.opacity = '0'
            document.body.appendChild(ta)
            ta.select()
            document.execCommand('copy')
            document.body.removeChild(ta)
        }
        this.copied = true
        setTimeout(() => this.copied = false, 2000)
    },
}))

Alpine.start()
