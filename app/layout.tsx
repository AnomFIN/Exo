import type { Metadata } from 'next'
import './globals.css'
import Navigation from '@/components/Navigation'

export const metadata: Metadata = {
  title: 'EXVATOR Oy | Maansiirto, Konekauppa & Vienti | Järvenpää',
  description: 'EXVATOR Oy tarjoaa ammattimaista maansiirtoa, konekauppaa ja vientipalveluita. Luotettava kumppani kaivuutöihin ja suoraan konekauppaan ilman välikäsiä. Järvenpää.',
  keywords: 'maansiirto, konekauppa, vienti, kaivuutyöt, Järvenpää, EXVATOR',
  openGraph: {
    title: 'EXVATOR Oy | Maansiirto, Konekauppa & Vienti',
    description: 'EXVATOR Oy tarjoaa ammattimaista maansiirtoa, konekauppaa ja vientipalveluita.',
    type: 'website',
    locale: 'fi_FI',
    siteName: 'EXVATOR Oy',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="fi">
      <head>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
      </head>
      <body className="font-sans bg-[#0a0a0a] text-white">
        <Navigation />
        {children}
      </body>
    </html>
  )
}
