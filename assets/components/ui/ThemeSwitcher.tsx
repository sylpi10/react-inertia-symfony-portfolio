import useTheme from "../../hooks/useTheme";
import { THEMES } from "../../lib/themes";

export default function ThemeSwitcher() {
    const { theme, setTheme } = useTheme();

    // un bouton par thème déclaré dans lib/themes.ts
    return (
        <div className="theme-switcher" role="group" aria-label="Thème">
            <ul>
                {THEMES.map(({ id, label }) => (
                    <li key={id}>
                        <button
                            type="button"
                            onClick={() => setTheme(id)}
                            aria-pressed={theme === id}
                        >
                            {label}
                        </button>
                    </li>
                ))}
            </ul>
        </div>
    );
}
