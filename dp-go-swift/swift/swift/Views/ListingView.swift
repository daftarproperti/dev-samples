//
//  ListingView.swift
//  swift
//
//  Created by airm1 on 11/10/24.
//

import SwiftUI

struct ListingView: View {
    var listingManager = ListingManager()
    @State var data: ListingResponse?
    
    var body: some View {
        NavigationView {
            VStack {
                if data != nil {
                    ScrollView {
                        VStack(spacing: 16) {
                            ForEach(data?.listings ?? []) { listing in
                                NavigationLink {
                                    ListingDetailView(listing: listing)
                                } label: {
                                    VStack(spacing: 16) {
                                        AsyncImage(url: URL(string: listing.picture_urls[0]),
                                                   content: { image in
                                            image.resizable()
                                                .aspectRatio(contentMode: .fit)
                                                .frame(maxWidth: .infinity, maxHeight: 320)
                                        },
                                                   placeholder: {
                                            ProgressView().padding().frame( height: 320)
                                        })
                                        
                                        Text(listing.description).padding([.horizontal, .bottom], 16).truncationMode(.tail).frame(maxWidth: .infinity, maxHeight: 40, alignment: .leading)
                                            .font(.system(size: 18))
                                    }
                                    .frame(maxWidth: .infinity)
                                    .background(Color.blue.opacity(0.1))
                                    .cornerRadius(10)
                                }.frame(maxWidth: .infinity)
                            }
                        }
                        .padding()
                    }
                } else {
                    ProgressView().task {
                        do {
                            data = try await listingManager.fetch(input: ListingRequest(page: 1, limit: 20))
                        } catch {
                            print("Error getting data: \(error)")
                        }
                    }
                }
            }
            .padding().navigationTitle("Listing")
        }
    }
}

#Preview {
    ListingView()
}
