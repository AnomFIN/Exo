export default function MachineTradeSection() {
  const features = [
    'Välittää ja myy koneita suoraan',
    'Järjestää kuljetukset Euroopasta',
    'Hoitaa paperityöt ja logistiikan',
    'Voi viedä myös asiakkaan omia koneita',
  ]

  const steps = [
    { number: '01', label: 'Tarjous' },
    { number: '02', label: 'Tarkastus' },
    { number: '03', label: 'Dokumentointi' },
    { number: '04', label: 'Kuljetus' },
    { number: '05', label: 'Satama' },
    { number: '06', label: 'Toimitus' },
  ]

  const trustPoints = [
    { icon: '🔍', text: 'Koneet tarkastetaan ennen kauppaa' },
    { icon: '💰', text: 'Läpinäkyvä hinnoittelu' },
    { icon: '⚡', text: 'Nopeat vastaukset' },
    { icon: '📋', text: 'Täysi dokumentaatio' },
  ]

  return (
    <section id="konekauppa" className="py-24 bg-[#0a0a0a]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-16">
          <div className="flex items-center gap-4 mb-4">
            <div className="w-12 h-px bg-[#F5C518]" />
            <span className="text-[#F5C518] text-sm font-bold uppercase tracking-widest">Erikoisosaaminen</span>
          </div>
          <h2 className="text-4xl sm:text-5xl font-black text-white mb-4">
            Konekauppa & Vienti
          </h2>
          <p className="text-gray-400 text-lg max-w-2xl">
            Suora konekauppa ilman välikäsiä — hankimme, tarkastamme ja toimitamme koneet Euroopasta maailmalle.
          </p>
        </div>

        <div className="grid lg:grid-cols-2 gap-16 items-start">
          {/* Left: Features */}
          <div>
            <h3 className="text-2xl font-black text-white mb-8">Mitä teemme</h3>
            <div className="space-y-4 mb-12">
              {features.map((feature, i) => (
                <div key={i} className="flex items-start gap-4 p-4 bg-[#1a1a1a] border border-[#2a2a2a] hover:border-[#F5C518]/40 transition-colors duration-300">
                  <div className="w-6 h-6 rounded-full bg-[#F5C518] flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg className="w-3 h-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span className="text-gray-200 font-medium">{feature}</span>
                </div>
              ))}
            </div>

            {/* Trust indicators */}
            <h3 className="text-2xl font-black text-white mb-6">Miksi valita meidät</h3>
            <div className="grid grid-cols-2 gap-4">
              {trustPoints.map((point, i) => (
                <div key={i} className="bg-[#1a1a1a] border border-[#2a2a2a] p-4 text-center">
                  <div className="text-2xl mb-2">{point.icon}</div>
                  <p className="text-xs text-gray-300 font-medium">{point.text}</p>
                </div>
              ))}
            </div>
          </div>

          {/* Right: Process + Map */}
          <div>
            <h3 className="text-2xl font-black text-white mb-8">Prosessi</h3>
            
            {/* Steps */}
            <div className="relative mb-12">
              <div className="grid grid-cols-3 gap-3">
                {steps.map((step, i) => (
                  <div key={i} className="relative">
                    <div className="bg-[#1a1a1a] border border-[#2a2a2a] p-4 text-center hover:border-[#F5C518]/50 transition-colors duration-300">
                      <div className="text-[#F5C518] font-black text-lg">{step.number}</div>
                      <div className="text-white text-xs font-bold uppercase tracking-wide mt-1">{step.label}</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Europe → Africa visualization */}
            <div className="bg-[#1a1a1a] border border-[#2a2a2a] p-6">
              <h4 className="text-sm font-bold text-[#F5C518] uppercase tracking-widest mb-6">Vientialue</h4>
              <div className="flex items-center justify-between">
                <div className="text-center">
                  <div className="text-3xl mb-2">🇪🇺</div>
                  <div className="text-white font-bold text-sm">Eurooppa</div>
                  <div className="text-gray-500 text-xs">Lähde</div>
                </div>

                <div className="flex-1 mx-4 flex flex-col items-center gap-2">
                  <div className="text-[#F5C518] text-xs font-bold uppercase tracking-widest">Suora toimitus</div>
                  <div className="w-full flex items-center gap-1">
                    <div className="flex-1 h-px bg-[#F5C518]/60" />
                    <div className="text-[#F5C518]">✈</div>
                    <div className="text-[#F5C518]">🚢</div>
                    <div className="flex-1 h-px bg-[#F5C518]/60" />
                  </div>
                  <div className="text-gray-500 text-xs">Ilman välikäsiä</div>
                </div>

                <div className="text-center">
                  <div className="text-3xl mb-2">🌍</div>
                  <div className="text-white font-bold text-sm">Afrikka</div>
                  <div className="text-gray-500 text-xs">Kohde</div>
                </div>
              </div>

              {/* SVG route visualization */}
              <div className="mt-6 pt-6 border-t border-[#2a2a2a]">
                <svg viewBox="0 0 300 120" className="w-full h-20 opacity-60">
                  <ellipse cx="60" cy="40" rx="30" ry="25" fill="#2a2a2a" stroke="#F5C518" strokeWidth="1" />
                  <text x="60" y="44" textAnchor="middle" fill="#F5C518" fontSize="8" fontWeight="bold">EU</text>
                  <path d="M 90 50 Q 150 20 210 70" stroke="#F5C518" strokeWidth="1.5" strokeDasharray="4 3" fill="none" />
                  <ellipse cx="240" cy="75" rx="35" ry="30" fill="#2a2a2a" stroke="#F5C518" strokeWidth="1" />
                  <text x="240" y="79" textAnchor="middle" fill="#F5C518" fontSize="8" fontWeight="bold">AFR</text>
                  <polygon points="205,65 215,70 205,75" fill="#F5C518" />
                </svg>
              </div>
            </div>

            <div className="mt-6">
              <a
                href="#yhteystiedot"
                className="inline-flex items-center gap-2 bg-[#F5C518] text-black px-6 py-3 font-black uppercase tracking-widest text-sm hover:bg-yellow-400 transition-colors duration-200 w-full justify-center"
              >
                Pyydä tarjous viennistä
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
