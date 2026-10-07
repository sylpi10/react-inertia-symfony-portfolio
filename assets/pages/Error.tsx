import { Link } from "@inertiajs/react";
import NavArrow from "../components/ui/NavArrow";

// pistes pour repartir ; sans image ni donnée en base : la page doit s'afficher
// même quand l'erreur vient de la base (500)
const links = [
    {
        href: "/",
        name: "Accueil",
        teaser: "Développeur React / Symfony pour votre équipe",
    },
    {
        href: "/creation-site-web",
        name: "Création de site web",
        teaser: "Sites vitrines et refontes pour indépendants et TPE",
    },
    {
        href: "/#contact",
        name: "Me contacter",
        teaser: "Une question, un projet ? Écrivez-moi",
    },
];

// rendue par InertiaErrorListener, avec le code HTTP de l'erreur
export default function Error({ status }: { status: number }) {
    const notFound = status === 404;

    return (
        <div className="section-container error-page">
            <p className="error-status" aria-hidden="true">
                {status}
            </p>
            <h1>{notFound ? "Page introuvable" : "Une erreur est survenue"}</h1>
            <p className="error-message">
                {notFound
                    ? "Cette page n’existe pas, ou plus. Voici de quoi repartir :"
                    : "Le problème vient de mon côté, réessayez un peu plus tard. En attendant :"}
            </p>

            <nav className="error-links" aria-label="Pages du site">
                {links.map((link) => (
                    <Link
                        key={link.href}
                        href={link.href}
                        className="error-link"
                    >
                        <span className="text">
                            <span className="name">{link.name}</span>
                            <span className="teaser">{link.teaser}</span>
                        </span>
                        <NavArrow direction="next" />
                    </Link>
                ))}
            </nav>
        </div>
    );
}
