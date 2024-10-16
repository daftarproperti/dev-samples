//
//  ModalView.swift
//  swift
//
//  Created by airm1 on 11/10/24.
//

import SwiftUI

struct ModalView: View {
    var listingId: String
    var contactManager = ContactManager()
    @State private var input: DecryptRequest = DecryptRequest()
    @State var data: DecryptResponse?
    
    var body: some View {
        VStack {
            if input.signature != nil {
                if data != nil {
                    VStack {
                        Text("Hubungi Penjual via WA").font(.system(size: 20))
                        Button(action: {
                            //
                        }) {
                            Text(data?.decryptedContact ?? "").padding().font(.system(size: 18))
                                .frame(maxWidth: .infinity)
                        }
                        .buttonBorderShape(.roundedRectangle)
                        .buttonStyle(.bordered)
                        .controlSize(.regular)
                        .tint(.green)
                    }
                    .padding()
                } else {
                    ProgressView().task {
                        do {
                            data = try await contactManager.decrypt(input: input)
                        } catch {
                            print("Error getting data: \(error)")
                        }
                    }
                }
            } else {
                WebView(input: $input, urlString: "https://reveal.daftarproperti.org/witness?referrerId=jelajahrumah.id&listingId=\(listingId)").edgesIgnoringSafeArea(.all)
            }
        }.presentationDetents([.medium]).frame(maxHeight: 380)
    }
}

#Preview {
    ModalView(listingId: "445939372077962516")
}
