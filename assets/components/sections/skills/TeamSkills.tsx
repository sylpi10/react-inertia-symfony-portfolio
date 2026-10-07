const skills = [
    {
        title: "Frontend",
        description:
            "React et TypeScript, avec Next.js ou Inertia pour le rendu serveur. Intégration HTML/CSS soignée, accessibilité, UX/UI et performances web (Core Web Vitals).",
    },
    {
        title: "Backend",
        description:
            "Symfony et PHP : modélisation avec Doctrine, API, back-offices EasyAdmin. Développement frontend et fonctionnalités custom sur Magento (e-commerce).",
    },
    {
        title: "Méthodes",
        description:
            "Git et revues de code, travail avec chefs de projet et designers (Trello, ClickUp, Asana, Slack). Architecture en couches (services, interfaces, DTO) sur le Labelmaker chez Ludilabel.",
    },
    {
        title: "Production",
        description:
            "Déploiement, hébergement, DNS et messagerie. SEO technique et suivi des Core Web Vitals.",
    },
    {
        title: "IA",
        description:
            "Claude Code intégré à mon workflow pour accélérer le développement, avec relecture systématique du code produit.",
    },
];
/**
 * MODE : TEAM
 */
export default function TeamSkills() {
    return (
        <div className="section-container services-container">
            <h2 className="section-title">Ce que j’apporte à une équipe</h2>
            <p className="services-intro">
                5 ans dans une équipe e-commerce, de la refonte d’une boutique à
                un outil de personnalisation en production : je sais m’intégrer
                à une base de code existante et la faire avancer.
            </p>

            <ul className="services-list">
                {skills.map((skill, index) => (
                    <li key={skill.title} className="service">
                        <div className="service-title">
                            <span className="service-number" aria-hidden="true">
                                {String(index + 1).padStart(2, "0")}
                            </span>
                            <h3>{skill.title}</h3>
                        </div>
                        <div className="service-body">
                            <div className="service-description">
                                <p>{skill.description}</p>
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
