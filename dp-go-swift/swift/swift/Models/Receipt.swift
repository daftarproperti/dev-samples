//
//  Receipt.swift
//  swift
//
//  Created by airm1 on 11/10/24.
//

import Foundation

struct Receipt: Codable {
    var timestamp: Int
    var clientPhoneHash2x: String
    var encryptedPhoneSalt: String
    var listingId: String
    var encryptedContact: String
    var referrerId: String
}

struct ReceiptResponse: Codable {
    var receipt: Receipt
    var signature: String
}
