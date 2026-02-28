export default function JoniStory() {
  return (
    <section id="tarina" className="py-24 bg-[#0a0a0a]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          {/* Text */}
          <div>
            <div className="flex items-center gap-4 mb-4">
              <div className="w-12 h-px bg-[#F5C518]" />
              <span className="text-[#F5C518] text-sm font-bold uppercase tracking-widest">Jonin tarina</span>
            </div>
            <h2 className="text-4xl sm:text-5xl font-black text-white mb-8">
              Rakensin tämän<br />
              <span className="text-[#F5C518]">omin käsin.</span>
            </h2>

            <div className="space-y-5 text-gray-300 text-base leading-relaxed">
              <p>
                Maanrakennusyrittäjäksi ei synnytä — siihen kasvetaan. Minun tieni alkoi vuosia sitten, kaivinkoneessa, märässä savessa, kun muut nukkuivat. Opettelin kaikki virheiden kautta, eikä kukaan jaellut opastusta ilmaiseksi.
              </p>
              <p>
                Näin läheltä, miten alalla voi tehdä asiat huonosti: epäselvät hinnat, lupaukset joita ei pidetä, asiakkaat jotka jäävät yksin ongelmien kanssa. Se sai minut ajattelemaan: mitä jos tekisi sen eri tavalla?
              </p>
              <p>
                EXVATOR syntyi siitä halusta. Halusin luoda yrityksen, jossa asiakas saa suoran vastauksen, selvän hinnan ja työn joka valmistuu ajallaan. Ei lupailla turhia — tehdään mitä sanotaan.
              </p>
              <p>
                Kun konekauppa ja vienti tulivat kuvaan mukaan, tajusin että sama periaate toimii siellä. Koneet tarkastetaan, hinnat ovat läpinäkyvät, paperit hoidetaan kuntoon. Asiakas tietää aina missä mennään.
              </p>
              <p className="text-[#F5C518] font-bold">
                Tämä ei ole vain työtä. Se on tapa tehdä asiat oikein.
              </p>
            </div>

            <div className="mt-8 flex items-center gap-4">
              <div className="w-12 h-12 rounded-full bg-[#F5C518] flex items-center justify-center text-black font-black text-lg">
                J
              </div>
              <div>
                <div className="text-white font-bold">Joni Kouki</div>
                <div className="text-gray-500 text-sm">Toimitusjohtaja, EXVATOR Oy</div>
              </div>
            </div>
          </div>

          {/* Photo placeholder */}
          <div className="relative">
            <div className="aspect-[3/4] bg-[#1a1a1a] border border-[#2a2a2a] flex flex-col items-center justify-center p-8 relative overflow-hidden">
              {/* Corner accents */}
              <div className="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-[#F5C518]" />
              <div className="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-[#F5C518]" />
              <div className="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-[#F5C518]" />
              <div className="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-[#F5C518]" />

              <div className="text-6xl mb-6 opacity-30">📷</div>
              <p className="text-gray-600 text-sm text-center leading-relaxed max-w-xs italic">
                &ldquo;Documentary-style portrait photo of a Finnish earthmoving entrepreneur named Joni, late 30s–40s, wearing work jacket and safety vest, standing beside an excavator at a construction site during golden hour&rdquo;
              </p>
              <div className="mt-6 px-3 py-1 bg-[#F5C518]/10 border border-[#F5C518]/30">
                <span className="text-[#F5C518] text-xs font-bold uppercase tracking-widest">Kuva tulossa</span>
              </div>
            </div>

            {/* Decorative element */}
            <div className="absolute -bottom-4 -right-4 w-24 h-24 border-b-4 border-r-4 border-[#F5C518] opacity-30" />
          </div>
        </div>
      </div>
    </section>
  )
}
