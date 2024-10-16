//
//  ListingDetailView.swift
//  swift
//
//  Created by airm1 on 09/10/24.
//

import SwiftUI

struct ListingDetailView: View {
    var listing: Listing
    @State private var isOpenModal = false
    
    var body: some View {
        VStack {
            ScrollView {
                VStack {
                    AsyncImage(url: URL(string: listing.picture_urls[0]),
                               content: { image in
                        image.resizable()
                            .aspectRatio(contentMode: .fit)
                            .frame(maxWidth: .infinity, maxHeight: 420)
                    },
                               placeholder: {
                        ProgressView().padding().frame( height: 420)
                    })
                    
                    VStack(spacing: 16) {
                        Text("Rp. \(listing.price)").font(.system(size: 32, weight: .bold)).frame(maxWidth: .infinity, alignment: .leading)
                        
                        Text(listing.address).font(.system(size: 16)).frame(maxWidth: .infinity, alignment: .leading)
                        
                        Text("Deskripsi").font(.system(size: 20, weight: .semibold)).frame(maxWidth: .infinity, alignment: .leading)
                        Text(listing.description).font(.system(size: 16)).frame(maxWidth: .infinity, alignment: .leading)
                        
                    }.frame(maxWidth: .infinity, maxHeight: .infinity, alignment: .leading).padding()
                    
                }.frame(maxWidth: .infinity, maxHeight: .infinity)
            }
            Spacer()
            
            HStack {
                Text("Penjual")
                
                Spacer()
                
                Button(action: {
                    isOpenModal = true
                }) {
                    Text("Hubungi")
                }
                .buttonStyle(.borderedProminent)
                .controlSize(.large)
            }.padding().overlay(Divider(), alignment: .top).frame(maxWidth: .infinity)
        }.sheet(isPresented: $isOpenModal) {
            ModalView(listingId: "\(listing.dp_id)")
        }
    }
}

#Preview {
    ListingDetailView(listing: ListingDummy)
}
