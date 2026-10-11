import { Link } from "@inertiajs/react";
import Footer from "../components/sections/Footer";
import Contact from "../components/sections/contact/Contact";
import { projectPath } from "../lib/paths";
import { ProjectProps } from "../types/projects";

const CLIENT_PATH = "/creation-site-web";

// réalisations pour des clients ou des usages toulousains, dans l'ordre d'affichage
const localSlugs = ["toulouse-sport-club", "la-cuisine-de-maha"];

// page de référencement local : contenu statique, seuls les projets viennent du serveur
export default function LocalToulouse({
    projects,
}: {
    projects: ProjectProps[];
}) {
    const localProjects = localSlugs
        .map((slug) => projects.find((p) => p.slug === slug))
        .filter((p) => p !== undefined);

    const servicesPath: string = `${CLIENT_PATH}#services`;
    const projectsPath: string = `${CLIENT_PATH}#projects`;
    const tarifsPath: string = `${CLIENT_PATH}#tarifs`;

    return (
        <>
            <div className="section-container local-container">
                <h1 className="section-title">
                    Développeur web freelance à Toulouse
                </h1>
                <p className="local-intro">
                    Installé à Toulouse, je crée et fais évoluer des sites et
                    des applications web pour les indépendants, TPE et PME de la
                    ville et de son agglomération : Blagnac, Colomiers, Balma,
                    Ramonville, Labège, Tournefeuille. <br />
                    Nous pouvons nous rencontrer sur place ou échanger en visio.
                </p>

                <section>
                    <h2>Création de site web à Toulouse</h2>
                    <p>
                        Un site vitrine 100 % sur mesure, rapide et bien
                        référencé, avec un espace d’administration pour modifier
                        vous-même vos textes, vos photos et vos offres. Vous
                        avez un seul interlocuteur, du premier échange à la mise
                        en ligne, puis pour l’hébergement et la maintenance.{" "}
                        <br />
                        Le détail des prestations est sur la page{" "}
                        <Link href={servicesPath}>création de site web</Link>.
                    </p>
                </section>

                <section>
                    <h2>Refonte et application web sur mesure</h2>
                    <p>
                        Un site lent, daté ou difficile à faire évoluer ? Je le
                        reconstruis sur une base moderne en conservant vos
                        contenus et votre référencement. Pour un outil métier ou
                        une plateforme, je développe en React, Next.js et
                        Symfony, avec cinq ans d’expérience sur une boutique en
                        ligne à fort trafic.
                    </p>
                </section>

                <section>
                    <h2>Référencement local à Toulouse</h2>
                    <p>
                        Être trouvé par les clients de votre secteur se joue sur
                        des points précis : un site qui s’affiche vite, des
                        pages dédiées à votre zone d’activité, des données
                        structurées que Google comprend et une fiche
                        d’établissement cohérente avec le site. Chaque projet
                        est contrôlé page par page avec les outils de Google.
                    </p>
                </section>

                <section>
                    <h2>Pour qui ?</h2>
                    <p>
                        Artisans, commerçants, professions libérales,
                        associations et petites entreprises de Toulouse et de
                        Haute-Garonne. Chaque projet est construit sur mesure,
                        du site de quelques pages à l’application complète,
                        partout en France si vous êtes plus loin.
                    </p>
                </section>

                {localProjects.length > 0 && (
                    <section>
                        <h2>Réalisations à Toulouse</h2>
                        <ul className="local-cards">
                            {localProjects.map((project) => (
                                <li key={project.slug} className="local-card">
                                    <h3>{project.name}</h3>
                                    {project.miniDescription && (
                                        <div
                                            className="local-card-text"
                                            dangerouslySetInnerHTML={{
                                                __html: project.miniDescription,
                                            }}
                                        />
                                    )}
                                    <Link
                                        href={projectPath(
                                            project.slug,
                                            "client",
                                        )}
                                        aria-label={`Voir le projet ${project.name}`}
                                    >
                                        Voir le projet
                                    </Link>
                                </li>
                            ))}
                        </ul>
                        <p>
                            D’autres réalisations, à Toulouse et ailleurs, sont
                            présentées dans{" "}
                            <Link href={projectsPath}>les projets</Link>.
                        </p>
                    </section>
                )}

                <section className="local-cta">
                    <p>
                        Un projet à Toulouse ? Un site vitrine démarre à partir
                        de 1 500 €, et le devis est gratuit et sans engagement.
                    </p>
                    <div className="local-actions">
                        <Link
                            href={tarifsPath}
                            className="local-button secondary"
                        >
                            Voir les tarifs
                        </Link>
                    </div>
                </section>

                <section className="local-author">
                    <p>
                        <strong>Sylvain Pillet</strong>
                        <br />
                        Développeur web freelance à Toulouse, formé à l’Adrar et
                        à la 3W Academy (Toulouse). <br />
                        Plusieurs clients accompagnés depuis 2020.
                    </p>
                </section>
            </div>
            {/* id : racine des styles du formulaire (contact.scss), comme sur les accueils */}
            <section id="contact">
                <Contact audience="client" />
            </section>
            <Footer />
        </>
    );
}
