const STATUS_COLORS = {
    active: 'var(--status-ok)',
    confirmed: 'var(--status-ok)',
    pending: 'var(--status-warning)',
    paused: 'var(--status-warning)',
    cancelled: 'var(--status-danger)',
    completed: 'var(--status-muted)',
    archived: 'var(--status-muted)',
};
/**
 * @param {string}
 * @returns {string}
 */
export function statusColor(status) {
    return STATUS_COLORS[status] ?? 'var(--status-muted)';
}
/**
 * @param {string}
 * @returns {string}
 */
export function statusLabel(status) {
    return window.STATUS_LABELS?.[status] ?? status;
}
export function registerStatusHelpers() {
    window.statusColor = statusColor;
    window.statusLabel = statusLabel;
}
