export function truncate(text: string, max: number): string {
    if (text.length <= max) return text;

    // place gardée pour « … » : on ne garde que max - 1 caractères
    const cut = text.slice(0, max - 1);
    // on recule jusqu'au dernier espace pour ne pas couper un mot
    const lastSpace = cut.lastIndexOf(" ");

    return cut.slice(0, lastSpace) + "…";
}
