import { Link, usePage } from "@inertiajs/react";
import logo from "../static/images/logo.webp";
import { useEffect, useState, useRef, ReactNode } from "react";
import { Audience } from "../types/audience";
import ThemeSwitcher from "../components/ui/ThemeSwitcher";
import { ThemeProvider } from "../contexts/ThemeContexts";

const CLIENT_PATH = "/creation-site-web";

// sections propres à chaque page (le choix de la page se fait dans le hero)
const navigation = {
    client: {
        links: [
            { href: `${CLIENT_PATH}#services`, label: "Services" },
            { href: `${CLIENT_PATH}#projects`, label: "Projets" },
            { href: `${CLIENT_PATH}#avis`, label: "Avis" },
            { href: `${CLIENT_PATH}#a-propos`, label: "À propos" },
            { href: `${CLIENT_PATH}#tarifs`, label: "Tarifs" },
            { href: `${CLIENT_PATH}#contact`, label: "Contact" },
        ],
    },
    team: {
        links: [
            { href: "/#competences", label: "Compétences" },
            { href: "/#projects", label: "Projets" },
            { href: "/#avis", label: "Avis" },
            { href: "/#a-propos", label: "À propos" },
            { href: "/#parcours", label: "Parcours" },
            { href: "/#contact", label: "Contact" },
        ],
    },
};

export default function Layout({ children }: { children: ReactNode }) {
    const { url, props, component } = usePage<{ audience?: Audience }>();
    // page projet : navigation de son mode ; page d'erreur : celle de l'accueil
    const { links } = navigation[props.audience ?? "team"];
    // accueil (Team) ou page création de site (Client)
    const isHomePage = component === "Team" || component === "Client";
    const [isMobileOpen, setIsMobileOpen] = useState(false);
    const headerRef = useRef(null);
    const burgerRef = useRef<HTMLButtonElement>(null);

    // menu mobile ouvert : Échap le ferme et rend le focus au bouton
    useEffect(() => {
        if (!isMobileOpen) return;
        const onKeyDown = (e: KeyboardEvent) => {
            if (e.key === "Escape") {
                setIsMobileOpen(false);
                burgerRef.current?.focus();
            }
        };
        addEventListener("keydown", onKeyDown);
        return () => removeEventListener("keydown", onKeyDown);
    }, [isMobileOpen]);

    // Ferme le menu mobile quand un lien est cliqué
    const handleLinkClick = () => {
        setIsMobileOpen(false);
    };

    return (
        <ThemeProvider>
            <a className="skip-link" href="#contenu">
                Aller au contenu
            </a>
            <header className="header" ref={headerRef}>
                <nav
                    aria-label="Navigation principale"
                    className={`navbar ${isHomePage ? "default-menu-class" : ""} ${isMobileOpen ? "mobile-nav" : ""}`}
                >
                    <span className="brand">
                        <Link href="/" onClick={handleLinkClick}>
                            <img
                                src={logo}
                                className="logo"
                                alt="Sylvain Pillet, accueil"
                                width="39"
                                height="60"
                            />
                        </Link>
                    </span>
                    <ul className="navlist" id="main-menu">
                        {links.map((link) => (
                            <li key={link.href}>
                                <Link
                                    href={link.href}
                                    onClick={handleLinkClick}
                                    className={
                                        url === link.href ? "active" : undefined
                                    }
                                    // liens vers des sections de la page : "location"
                                    aria-current={
                                        url === link.href
                                            ? "location"
                                            : undefined
                                    }
                                >
                                    {link.label}
                                    {/* étiré en largeur seulement (nav.scss) */}
                                    <svg
                                        viewBox="0 0 70 36"
                                        preserveAspectRatio="none"
                                    >
                                        <path d="M6.9739 30.8153H63.0244C65.5269 30.8152 75.5358 -3.68471 35.4998 2.81531C-16.1598 11.2025 0.894099 33.9766 26.9922 34.3153C104.062 35.3153 54.5169 -6.68469 23.489 9.31527" />
                                    </svg>
                                </Link>
                            </li>
                        ))}
                    </ul>

                    <ThemeSwitcher />

                    {/* bouton et non span : atteignable au clavier, annoncé comme bouton */}
                    <button
                        ref={burgerRef}
                        type="button"
                        className={`burger ${isMobileOpen ? "open" : ""}`}
                        onClick={() => setIsMobileOpen((prev) => !prev)}
                        aria-label={
                            isMobileOpen ? "Fermer le menu" : "Ouvrir le menu"
                        }
                        aria-expanded={isMobileOpen}
                        aria-controls="main-menu"
                    >
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </button>
                </nav>
            </header>

            {/* tabIndex -1 : cible du lien d'évitement ; inert : menu mobile ouvert,
                le focus ne part pas dans la page cachée derrière */}
            <main id="contenu" tabIndex={-1} inert={isMobileOpen}>
                {children}
            </main>
        </ThemeProvider>
    );
}
