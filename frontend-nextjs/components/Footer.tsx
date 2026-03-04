export default function Footer() {
  const currentYear = new Date().getFullYear()

  return (
    <footer className="bg-[#050505] border-t border-[#1a1a1a]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid md:grid-cols-3 gap-12 mb-12">
          {/* Logo & tagline */}
          <div>
            <div className="flex items-baseline gap-2 mb-4">
              <span className="text-3xl font-black text-[#F5C518] tracking-wider">EXVATOR</span>
              <span className="text-xs text-gray-600 font-medium">OY</span>
            </div>
            <p className="text-gray-500 text-sm leading-relaxed max-w-xs">
              Maansiirtoa, konekauppaa ja vientiä ilman välikäsiä. Luotettava suomalainen yrittäjä.
            </p>
            <div className="mt-6 inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/20 px-3 py-1.5">
              <span className="w-1.5 h-1.5 rounded-full bg-[#F5C518]" />
              <span className="text-[#F5C518] text-xs font-bold uppercase tracking-widest">Järvenpää, Suomi</span>
            </div>
          </div>

          {/* Quick links */}
          <div>
            <h4 className="text-white font-black uppercase tracking-widest text-sm mb-6">Sivut</h4>
            <ul className="space-y-3">
              {[
                { href: '#palvelut', label: 'Palvelut' },
                { href: '#konekauppa', label: 'Konekauppa & Vienti' },
                { href: '#tuotteet', label: 'Tuotteet' },
                { href: '#tarina', label: 'Jonin tarina' },
                { href: '#yhteystiedot', label: 'Yhteystiedot' },
              ].map((link) => (
                <li key={link.href}>
                  <a
                    href={link.href}
                    className="text-gray-500 hover:text-[#F5C518] transition-colors duration-200 text-sm flex items-center gap-2"
                  >
                    <span className="w-1.5 h-px bg-current" />
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Company info */}
          <div>
            <h4 className="text-white font-black uppercase tracking-widest text-sm mb-6">Yritystiedot</h4>
            <dl className="space-y-3 text-sm">
              {[
                ['Y-tunnus', '3291765-7'],
                ['Perustettu', '2022'],
                ['ALV', 'FI32917657'],
                ['Toimitusjohtaja', 'Joni Kouki'],
              ].map(([key, value]) => (
                <div key={key}>
                  <dt className="text-gray-600 text-xs uppercase tracking-wide">{key}</dt>
                  <dd className="text-gray-300 font-medium mt-0.5">{value}</dd>
                </div>
              ))}
              <div>
                <dt className="text-gray-600 text-xs uppercase tracking-wide">Osoite</dt>
                <dd className="text-gray-300 font-medium mt-0.5">
                  Loutinkatu 57 G 21<br />
                  04440 Järvenpää
                </dd>
              </div>
              <div>
                <dt className="text-gray-600 text-xs uppercase tracking-wide">Sähköposti</dt>
                <dd className="mt-0.5">
                  <a href="mailto:info@exvator.fi" className="text-[#F5C518] hover:text-yellow-400 transition-colors duration-200 font-medium">
                    info@exvator.fi
                  </a>
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <div className="border-t border-[#1a1a1a] pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <p className="text-gray-600 text-xs">
            © {currentYear} EXVATOR Oy. Kaikki oikeudet pidätetään.
          </p>
          <p className="text-gray-700 text-xs">
            ALV-velvollinen · Kaupparekisteri · Y-tunnus 3291765-7
          </p>
        </div>
      </div>
    </footer>
  )
}
