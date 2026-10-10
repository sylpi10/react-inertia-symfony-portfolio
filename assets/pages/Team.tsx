import { Link } from "@inertiajs/react";
import About from "../components/sections/about/About";
import Contact from "../components/sections/contact/Contact";
import Footer from "../components/sections/Footer";
import Hero from "../components/sections/hero/Hero";
import Parcours from "../components/sections/parcours/Parcours";
import Projects from "../components/sections/projects/Projects";
import TeamSkills from "../components/sections/skills/TeamSkills";
import { projectPath } from "../lib/paths";
import { ExperienceProps } from "../types/experiences";
import { ProjectProps } from "../types/projects";
import Reviews from "../components/sections/reviews/Reviews";
import { ReviewProps } from "../types/reviews";

type TeamProps = {
    projects: ProjectProps[];
    experiences: ExperienceProps[];
    reviews: ReviewProps[];
};

// accueil, pour les recruteurs, CTO et agences : textes distincts de /creation-site-web
// TODO textes provisoires
export default function Team({ projects, experiences, reviews }: TeamProps) {
    const ludilabelSlug = projects.find((p) => p.name === "Ludilabel")?.slug;
    const labelmakerSlug = projects.find((p) => p.name === "Labelmaker")?.slug;

    const ludilabelDetailsUrl =
        ludilabelSlug && projectPath(ludilabelSlug, "team");
    const labelmakerDetailsUrl =
        labelmakerSlug && projectPath(labelmakerSlug, "team");

    return (
        <>
            <section id="home">
                <Hero
                    audience="team"
                    text={"Développeur\nFrontend / Fullstack"}
                    intro={
                        <>
                            <p>
                                Développeur frontend avec une pratique
                                fullstack, basé à Toulouse. Je rejoins votre
                                équipe pour faire avancer vos produits React ou
                                Symfony.
                            </p>
                            <p>5 ans d’expérience e-commerce chez Ludilabel.</p>
                        </>
                    }

                    details={
                        <>
                            <p>
                                Formé comme Concepteur Développeur
                                d’Applications, j’ai mené le frontend de la
                                refonte de{" "}
                                {ludilabelDetailsUrl ? (
                                    <Link href={ludilabelDetailsUrl}>
                                        Ludilabel
                                    </Link>
                                ) : (
                                    "Ludilabel"
                                )}{" "}
                                et développé le{" "}
                                {labelmakerDetailsUrl ? (
                                    <Link href={labelmakerDetailsUrl}>
                                        Labelmaker
                                    </Link>
                                ) : (
                                    "Labelmaker"
                                )}
                                , l’outil de personnalisation d’étiquettes de la
                                boutique, en Symfony et React.
                            </p>
                            <p>
                                J'accompagne également depuis 2020 des
                                indépendant dans la création de leur projets.
                            </p>
                        </>
                    }
                    cta="Parlons de votre besoin"
                />
            </section>
            <section id="competences">
                <TeamSkills />
            </section>
            <section id="projects">
                <Projects projects={projects} audience="team" />
            </section>
            <section id="avis">
                <Reviews reviews={reviews} audience="team" />
            </section>
            <section id="a-propos">
                <About audience="team" />
            </section>
            <section id="parcours">
                <Parcours experiences={experiences} audience="team" />
            </section>
            <section id="contact">
                <Contact audience="team" />
            </section>
            <Footer />
        </>
    );
}
