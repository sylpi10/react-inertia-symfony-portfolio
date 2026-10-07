import { Link } from "@inertiajs/react";
import { Audience } from "../../../types/audience";
import CheckIcon from "../../ui/CheckIcon";

type SwitcherProps = {
    audience: Audience;
};
export function AudienceSwitch({ audience }: SwitcherProps) {
    // deux vraies pages (une par public) : le choix est un lien, pas un masquage
    const audienceLinks: { audience: Audience; href: string; label: string }[] =
        [
            {
                audience: "team",
                href: "/",
                label: "Un développeur pour votre équipe",
            },
            {
                audience: "client",
                href: "/creation-site-web",
                label: "Un site pour mon activité",
            },
        ];

    return (
        <nav className="audience-switch" aria-label="Vous cherchez">
            {audienceLinks.map((link) => (
                <Link
                    key={link.audience}
                    href={link.href}
                    className={
                        link.audience === audience ? "active" : undefined
                    }
                    aria-current={
                        link.audience === audience ? "page" : undefined
                    }
                >
                    {link.label}
                    <CheckIcon />
                </Link>
            ))}
        </nav>
    );
}
