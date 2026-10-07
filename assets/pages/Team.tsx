import About from "../components/sections/about/About";
import Contact from "../components/sections/contact/Contact";
import Footer from "../components/sections/Footer";
import Hero from "../components/sections/hero/Hero";
import Parcours from "../components/sections/parcours/Parcours";
import Projects from "../components/sections/projects/Projects";
import TeamSkills from "../components/sections/skills/TeamSkills";
import { ExperienceProps } from "../types/experiences";
import { ProjectProps } from "../types/projects";

type TeamProps = {
    projects: ProjectProps[];
    experiences: ExperienceProps[];
};

// accueil, pour les recruteurs, CTO et agences : textes distincts de /creation-site-web
// TODO textes provisoires
export default function Team({ projects, experiences }: TeamProps) {
    return (
        <>
            <section id="home">
                <Hero
                    audience="team"
                    text={"Développeur\nFrontend / Fullstack"}
                    intro={
                        <>
                            Développeur frontend avec une pratique fullstack,
                            basé à Toulouse. Je rejoins votre équipe pour faire
                            avancer vos produits React/TypeScript ou Symfony.{" "}
                            <br />5 ans d’expérience e-commerce chez Ludilabel.
                        </>
                    }

                    details={
                        <>
                            Formé comme Concepteur Développeur d’Applications,
                            j’ai mené le frontend de la refonte de Ludilabel
                            puis développé le Labelmaker, l’outil de
                            personnalisation d’étiquettes de la boutique, en
                            Symfony et React.
                        </>
                    }
                    cta="Parlons de votre équipe"
                />
            </section>
            <section id="competences">
                <TeamSkills />
            </section>
            <section id="projects">
                <Projects projects={projects} audience="team" />
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
