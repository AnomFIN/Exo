export default function ProductCards() {
  const products = [
    {
      icon: '⛏️',
      title: 'Luiskakauhat',
      description: 'Luiskatöihin ja maanmuokkaukseen optimoitu kauhavalikoima. Ideaali rinnetöihin ja maastomuotoiluun.',
    },
    {
      icon: '🪣',
      title: 'Sorakauhat',
      description: 'Raskaaseen soran ja irtotavaran käsittelyyn suunnitellut kauhat. Kestävä rakenne kovaan käyttöön.',
    },
    {
      icon: '🔧',
      title: 'Kaapelikauhat',
      description: 'Kaapeli- ja yhdyskuntarakentamiseen soveltuvat erikoiskauhat. Tarkka työ ahtaissakin olosuhteissa.',
    },
    {
      icon: '🔗',
      title: 'Pikaliittimet',
      description: 'Nopeat lisälaitteiden vaihdot tehokkuuden maksimoimiseksi. Yhteensopiva useimpien konemerkkien kanssa.',
    },
    {
      icon: '🏗️',
      title: 'Trukkipiikit',
      description: 'Kaivinkoneisiin asennettavat trukkipiikkiadapterit. Monikäyttöinen ratkaisu materiaalien siirtoon.',
    },
    {
      icon: '💧',
      title: 'Hydrauliikkaletkut',
      description: 'Korkean paineen hydrauliikkaletkusarjat kaivinkoneisiin. Kestävät materiaalit ja varmat liitokset.',
    },
    {
      icon: '🦾',
      title: 'Lajittelukourat',
      description: 'Tarkkuuslajitteluun ja materiaalinkäsittelyyn suunnitellut kourat. Tehokas ja tarkka työkalu.',
    },
    {
      icon: '⚙️',
      title: 'Työkoneiden lisävarusteet',
      description: 'Monipuolinen valikoima lisävarusteita ja osia työkoneisiin. Kaikki tarvitsemasi yhdestä paikasta.',
    },
  ]

  return (
    <section id="tuotteet" className="py-24 bg-[#0f0f0f]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-16">
          <div className="flex items-center gap-4 mb-4">
            <div className="w-12 h-px bg-[#F5C518]" />
            <span className="text-[#F5C518] text-sm font-bold uppercase tracking-widest">Tuotteet</span>
          </div>
          <h2 className="text-4xl sm:text-5xl font-black text-white mb-4">
            Konekaupan tuotteet
          </h2>
          <p className="text-gray-400 text-lg max-w-2xl">
            Laadukkaat lisälaitteet ja varaosat kaivinkoneisiin ja työkoneisiin. Kysy hinta suoraan meiltä.
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {products.map((product, index) => (
            <div
              key={product.title}
              className="bg-[#1a1a1a] border border-[#2a2a2a] p-6 hover:border-[#F5C518]/50 transition-all duration-300 group flex flex-col"
              style={{ animationDelay: `${index * 0.05}s` }}
            >
              <div className="text-3xl mb-4">{product.icon}</div>
              <h3 className="text-sm font-black text-white uppercase tracking-wide mb-2 group-hover:text-[#F5C518] transition-colors duration-200">
                {product.title}
              </h3>
              <p className="text-gray-500 text-xs leading-relaxed mb-6 flex-1">
                {product.description}
              </p>
              <a
                href="#yhteystiedot"
                className="inline-flex items-center justify-center bg-[#F5C518] text-black px-4 py-2 text-xs font-black uppercase tracking-widest hover:bg-yellow-400 transition-colors duration-200 w-full"
              >
                Kysy hinta
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
