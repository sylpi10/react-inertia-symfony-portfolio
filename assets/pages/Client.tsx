import About from "../components/sections/about/About";
import Footer from "../components/sections/Footer";
import Hero from "../components/sections/hero/Hero";
import Projects from "../components/sections/projects/Projects";
import Services from "../components/sections/skills/Services";
import Contact from "../components/sections/contact/Contact";
import Pricing from "../components/sections/pricing/Pricing";
import Process from "../components/sections/pricing/Process";
import { ProjectProps } from "../types/projects";
import { OfferProps } from "../types/offers";
import { projectPath } from "../lib/paths";
import { Link } from "@inertiajs/react";
import { ReviewProps } from "../types/reviews";
import Reviews from "../components/sections/reviews/Reviews";

type ClientProps = {
    projects: ProjectProps[];
    offers: OfferProps[];
    reviews: ReviewProps[];
};

// les id servent d'ancres pour la nav et de racine aux styles (#home, #a-propos...)
export default function Client({ projects, offers, reviews }: ClientProps) {
    const guideSlug = projects.find((p) => p.slug === "directicimes")?.slug;

    const neuroPsySlug = projects.find(
        (p) => p.slug === "cabinet-de-neuro-psy",
    )?.slug;
    const mahaSlug = projects.find(
        (p) => p.slug === "la-cuisine-de-maha",
    )?.slug;
    const utopixSlug = projects.find((p) => p.slug === "utopix")?.slug;

    const guideDetailsUrl = guideSlug && projectPath(guideSlug, "client");
    const neuroPsyDetailsUrl =
        neuroPsySlug && projectPath(neuroPsySlug, "client");
    const mahaDetailsUrl = mahaSlug && projectPath(mahaSlug, "client");
    const utopixDetailsUrl = utopixSlug && projectPath(utopixSlug, "client");

    return (
        <>
            <section id="home">
                <Hero
                    audience="client"
                    text={"Création de sites web \n à Toulouse "}
                    intro={
                        <>
                            <p>
                                Développeur web freelance à Toulouse, je crée
                                des sites 100 % sur mesure avec un design
                                moderne pensé pour votre activité.
                            </p>
                            <p>
                                Depuis 2020, j’accompagne des indépendants et
                                des petites entreprises : <br />
                                {guideDetailsUrl ? (
                                    <Link rel="noopener" href={guideDetailsUrl}>
                                        Guide de haute montagne
                                    </Link>
                                ) : (
                                    "Guide de haute montagne"
                                )}
                                ,{" "}
                                {neuroPsyDetailsUrl ? (
                                    <Link
                                        rel="noopener"
                                        href={neuroPsyDetailsUrl}
                                    >
                                        Neuropsychologue
                                    </Link>
                                ) : (
                                    "Neuropsychologue"
                                )}
                                ,{" "}
                                {mahaDetailsUrl ? (
                                    <Link rel="noopener" href={mahaDetailsUrl}>
                                        Cheffe à domicile
                                    </Link>
                                ) : (
                                    "Cheffe à domicile"
                                )}
                                ,{" "}
                                {utopixDetailsUrl ? (
                                    <Link
                                        rel="noopener"
                                        href={utopixDetailsUrl}
                                    >
                                        lieu d’exposition
                                    </Link>
                                ) : (
                                    "lieu d’exposition"
                                )}
                                ... <br />
                                Ils gèrent aujourd’hui leur site en toute
                                autonomie.
                            </p>
                        </>
                    }
                    details={
                        <>
                            <p>
                                Chaque projet est contrôlé selon les standards
                                du web, vitesse testée avec les outils de
                                Google, référencement vérifié page par page.
                            </p>
                            <p>
                                Une fois le projet en ligne, vous êtes autonome
                                pour gérer tous vos contenus via un espace
                                d'administration.
                            </p>
                        </>
                    }
                    cta="Discutons de votre projet"
                />
            </section>
            <section id="services">
                <Services offers={offers} />
            </section>
            <section id="projects">
                <Projects projects={projects} audience="client" />
            </section>
            <section id="avis">
                <Reviews reviews={reviews} audience="client" />
            </section>
            <section id="a-propos">
                <About audience="client" />
            </section>

            <section id="tarifs">
                <Pricing />
            </section>
            <section id="deroule">
                <Process />
            </section>
            <section id="contact">
                <Contact audience="client" />
            </section>
            <Footer />
        </>
    );
}
