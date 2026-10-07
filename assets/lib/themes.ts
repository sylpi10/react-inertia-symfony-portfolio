export const THEMES = [
    { id: "light", label: "Clair" },
    { id: "dark", label: "Sombre" },
    // { id: "paper", label: "Papier" },
] as const;
export type ThemeId = (typeof THEMES)[number]["id"];
