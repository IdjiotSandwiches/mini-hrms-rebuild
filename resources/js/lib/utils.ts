import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { index as dashboardIndex } from '@/routes/v2/admin/dashboard';
import { index as attendanceIndex } from '@/routes/v2/take-attendance';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function getCurrentTime(onTimeUpdate: any) {
    let timerId: any = null;

    function getCurrentTime() {
        const now = new Date();
        const timeData = {
            day: `${now.toLocaleString('default', { weekday: 'long' })}, ${now.getDate()} ${now.toLocaleString('default', { month: 'long' })} ${now.getFullYear()}`,
            hours: now.getHours().toString().padStart(2, '0'),
            minutes: now.getMinutes().toString().padStart(2, '0'),
            seconds: now.getSeconds().toString().padStart(2, '0'),
        };

        onTimeUpdate(timeData);
        timerId = setTimeout(getCurrentTime, 1000);
    }

    getCurrentTime();

    return () => {
        if (timerId) {
clearTimeout(timerId);
}
    };
}

export function convertDate(date: string) {
    return new Date(date).toLocaleDateString('en-GB', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

export function convertTime(time: string) {
    return new Date(time).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

export function formatSeconds(secs: number) {
    const hours = Math.floor(secs / 3600);
    const minutes = Math.floor((secs % 3600) / 60);
    const seconds = secs % 60;

    const hh = String(hours).padStart(2, '0');
    const mm = String(minutes).padStart(2, '0');
    const ss = String(seconds).padStart(2, '0');

    return `${hh}:${mm}:${ss}`;
}

export function validateBoolean(bool: boolean|number|null) {
    if (bool == true) {
        return 'Yes';
    } else if (bool == false) {
        return 'No';
    } else {
        return '-';
    }
}

export function getAdminHome(bool: boolean|number|null) {
    if (bool == true) {
        return dashboardIndex();
    } else if (bool == false) {
        return attendanceIndex();
    } else {
        return '/';
    }
}
