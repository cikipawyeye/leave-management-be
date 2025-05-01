export function toTitleCase(str: string) {
    return str.replace(/\b\w/g, (c) => c.toUpperCase());
}

export function debounce<T extends (...args: string[]) => void>(callback: T, delay: number = 300) {
    let timer: ReturnType<typeof setTimeout>;

    return (...args: Parameters<T>) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            callback(...args);
        }, delay);
    };
}

export function formatLocaleDate(input?: string): string {
    if (!input) {
        return '';
    }

    const date = new Date(input);

    // Konversi ke zona waktu lokal Indonesia
    const dateOptions: Intl.DateTimeFormatOptions = {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'Asia/Jakarta',
    };

    const timeOptions: Intl.DateTimeFormatOptions = {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: 'Asia/Jakarta',
    };

    const tanggalFormatted = date.toLocaleDateString('id-ID', dateOptions);
    const waktuFormatted = date.toLocaleTimeString('id-ID', timeOptions);

    return `${tanggalFormatted}, pukul ${waktuFormatted}`;
}

export function ellipsisText(text: string, maxLength: number = 30): string {
    if (text.length <= maxLength) {
        return text;
    }

    return text.slice(0, maxLength) + '...';
}
