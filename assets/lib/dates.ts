export function formatDay(iso: string): string {
    return new Date(iso).toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "long",
        year: "numeric",
        timeZone: "Europe/Paris",
    });
}
