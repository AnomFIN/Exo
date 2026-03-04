export default function Services() {
  const services = [
    {
      icon: '⛏️',
      title: 'Kaivuutyöt',
      description:
        'Ammattimaiset kaivuutyöt rakennustyömaille, infrastruktuuriin ja maanmuokkaukseen. Käytössämme moderni kalusto ja kokeneet operaattorit — työ tehdään ajallaan ja sovitulla budjetilla.',
      features: ['Rakennuskaivannot', 'Putkikaivannot', 'Maanmuokkaus'],
    },
    {
      icon: '🏗️',
      title: 'Pohjatyö',
      description:
        'Luotettavat pohjatyöt rakennusten ja rakenteiden perustuksille. Huolellinen suunnittelu ja toteutus takaavat kestävän lopputuloksen — perustukset kuntoon kerralla oikein.',
      features: ['Perustuskaivannot', 'Täyttötyöt', 'Tiivistystyöt'],
    },
    {
      icon: '⏱️',
      title: 'Tuntityöt',
      description:
        'Joustava tuntilaskutus pieniin ja kiireellisiin töihin. Kone ja kuljettaja käytettävissä tarvittaessa — nopea reagointi ja luotettava palvelu ilman pitkiä sopimuksia.',
      features: ['Nopea saatavuus', 'Joustava aikataulutus', 'Läpinäkyvä laskutus'],
    },
  ]

  return (
    <section id="palvelut" className="py-24 bg-[#0f0f0f]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-16">
          <div className="flex items-center gap-4 mb-4">
            <div className="w-12 h-px bg-[#F5C518]" />
            <span className="text-[#F5C518] text-sm font-bold uppercase tracking-widest">Palvelut</span>
          </div>
          <h2 className="text-4xl sm:text-5xl font-black text-white">
            Palvelumme
          </h2>
          <p className="text-gray-400 mt-4 text-lg max-w-2xl">
            Tarjoamme kattavat maanrakennuspalvelut — pienistä tuntitöistä suuriin rakennusprojekteihin.
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-6">
          {services.map((service, index) => (
            <div
              key={service.title}
              className="bg-[#1a1a1a] border border-[#2a2a2a] p-8 hover:border-[#F5C518]/50 transition-all duration-300 group"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="text-4xl mb-6">{service.icon}</div>
              <div className="w-12 h-1 bg-[#F5C518] mb-6 group-hover:w-20 transition-all duration-300" />
              <h3 className="text-xl font-black text-white mb-3 uppercase tracking-wide">{service.title}</h3>
              <p className="text-gray-400 text-sm leading-relaxed mb-6">{service.description}</p>
              <ul className="space-y-2">
                {service.features.map((feature) => (
                  <li key={feature} className="flex items-center gap-2 text-sm text-gray-300">
                    <span className="w-1.5 h-1.5 rounded-full bg-[#F5C518] flex-shrink-0" />
                    {feature}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
