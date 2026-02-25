'use client'

import { useState } from 'react'

export default function ContactSection() {
  const [formData, setFormData] = useState({
    nimi: '',
    sahkoposti: '',
    puhelinnumero: '',
    viesti: '',
  })
  const [status, setStatus] = useState<'idle' | 'loading' | 'success' | 'error'>('idle')
  const [message, setMessage] = useState('')

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }))
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setStatus('loading')

    try {
      const res = await fetch('/api/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })
      const data = await res.json()

      if (res.ok) {
        setStatus('success')
        setMessage(data.message || 'Viesti lähetetty!')
        setFormData({ nimi: '', sahkoposti: '', puhelinnumero: '', viesti: '' })
      } else {
        setStatus('error')
        setMessage(data.error || 'Jokin meni pieleen. Yritä uudelleen.')
      }
    } catch {
      setStatus('error')
      setMessage('Palvelinvirhe. Yritä uudelleen.')
    }
  }

  const inputClass = 'w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white px-4 py-3 text-sm focus:outline-none focus:border-[#F5C518] transition-colors duration-200 placeholder-gray-600'

  return (
    <section id="yhteystiedot" className="py-24 bg-[#0f0f0f]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-16">
          <div className="flex items-center gap-4 mb-4">
            <div className="w-12 h-px bg-[#F5C518]" />
            <span className="text-[#F5C518] text-sm font-bold uppercase tracking-widest">Yhteystiedot</span>
          </div>
          <h2 className="text-4xl sm:text-5xl font-black text-white">
            Ota yhteyttä
          </h2>
          <p className="text-gray-400 mt-4 text-lg max-w-2xl">
            Pyydä tarjous tai kysy lisää. Vastaame nopeasti.
          </p>
        </div>

        <div className="grid lg:grid-cols-2 gap-16">
          {/* Contact info */}
          <div>
            <div className="space-y-8">
              <div>
                <h3 className="text-lg font-black text-white uppercase tracking-wide mb-4">EXVATOR Oy</h3>
                <div className="space-y-3">
                  <div className="flex items-start gap-3">
                    <span className="text-[#F5C518] text-lg">📍</span>
                    <div>
                      <div className="text-white font-medium">Loutinkatu 57 G 21</div>
                      <div className="text-gray-400">04440 Järvenpää, Suomi</div>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className="text-[#F5C518] text-lg">✉️</span>
                    <a href="mailto:info@exvator.fi" className="text-gray-300 hover:text-[#F5C518] transition-colors duration-200">
                      info@exvator.fi
                    </a>
                  </div>
                </div>
              </div>

              <div className="bg-[#1a1a1a] border border-[#2a2a2a] p-6">
                <h4 className="text-white font-bold uppercase tracking-wide text-sm mb-4">Yritystiedot</h4>
                <dl className="space-y-2 text-sm">
                  {[
                    ['Y-tunnus', '3291765-7'],
                    ['Perustettu', '2022'],
                    ['Yritysmuoto', 'Osakeyhtiö'],
                    ['ALV-numero', 'FI32917657'],
                    ['Toimitusjohtaja', 'Kouki Joni Joonas'],
                  ].map(([key, value]) => (
                    <div key={key} className="flex justify-between">
                      <dt className="text-gray-500">{key}</dt>
                      <dd className="text-gray-200 font-medium">{value}</dd>
                    </div>
                  ))}
                </dl>
              </div>

              <div className="border-l-4 border-[#F5C518] pl-6">
                <p className="text-gray-400 text-sm italic">
                  &ldquo;Vastaan yhteydenottoihin henkilökohtaisesti. Ei automaattivastauksia.&rdquo;
                </p>
                <p className="text-white font-bold text-sm mt-2">— Joni Kouki, EXVATOR Oy</p>
              </div>
            </div>
          </div>

          {/* Form */}
          <div>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                  Nimi *
                </label>
                <input
                  type="text"
                  name="nimi"
                  value={formData.nimi}
                  onChange={handleChange}
                  required
                  placeholder="Etunimi Sukunimi"
                  className={inputClass}
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                  Sähköposti *
                </label>
                <input
                  type="email"
                  name="sahkoposti"
                  value={formData.sahkoposti}
                  onChange={handleChange}
                  required
                  placeholder="sinä@example.fi"
                  className={inputClass}
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                  Puhelinnumero
                </label>
                <input
                  type="tel"
                  name="puhelinnumero"
                  value={formData.puhelinnumero}
                  onChange={handleChange}
                  placeholder="+358 40 123 4567"
                  className={inputClass}
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                  Viesti *
                </label>
                <textarea
                  name="viesti"
                  value={formData.viesti}
                  onChange={handleChange}
                  required
                  rows={5}
                  placeholder="Kerro tarpeistasi — pyydä tarjous tai kysy lisätietoja..."
                  className={`${inputClass} resize-none`}
                />
              </div>

              {status === 'success' && (
                <div className="bg-green-900/30 border border-green-600/50 text-green-400 px-4 py-3 text-sm font-medium">
                  ✓ {message}
                </div>
              )}

              {status === 'error' && (
                <div className="bg-red-900/30 border border-red-600/50 text-red-400 px-4 py-3 text-sm font-medium">
                  ✗ {message}
                </div>
              )}

              <button
                type="submit"
                disabled={status === 'loading'}
                className="w-full bg-[#F5C518] text-black px-8 py-4 font-black uppercase tracking-widest text-sm hover:bg-yellow-400 transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
              >
                {status === 'loading' ? 'Lähetetään...' : 'Lähetä viesti'}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  )
}
